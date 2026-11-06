<?php
/**
 * JayArt Core Router
 * Clean URL routing with dynamic parameters for cPanel hosting
 */

declare(strict_types=1);

class Router {
    private static array $routes = [];
    private static $notFoundHandler = null;

    /**
     * Register a GET route
     */
    public static function get(string $path, array|callable $handler): void {
        self::addRoute('GET', $path, $handler);
    }

    /**
     * Register a POST route
     */
    public static function post(string $path, array|callable $handler): void {
        self::addRoute('POST', $path, $handler);
    }

    /**
     * Register route for any HTTP method
     */
    public static function any(string $path, array|callable $handler): void {
        self::addRoute('GET', $path, $handler);
        self::addRoute('POST', $path, $handler);
    }

    /**
     * Internal Route Adder with regex pattern generator
     */
    private static function addRoute(string $method, string $path, array|callable $handler): void {
        $path = '/' . trim($path, '/');
        // Convert route pattern with {param} to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        self::$routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
            'rawPath' => $path
        ];
    }

    /**
     * Set 404 Not Found Handler
     */
    public static function setNotFound(array|callable $handler): void {
        self::$notFoundHandler = $handler;
    }

    /**
     * Resolve and Dispatch Request
     */
    public static function dispatch(?string $requestUri = null, ?string $requestMethod = null): void {
        $method = $requestMethod ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Handle URL parameter from .htaccess rewriting or REQUEST_URI
        if ($requestUri === null) {
            if (isset($_GET['url'])) {
                $uri = '/' . trim($_GET['url'], '/');
            } else {
                $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
                // Strip subfolder if running in subfolder
                $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
                $base = rtrim(str_replace(['/admin', '\\admin'], '', $scriptDir), '/');
                if (!empty($base) && str_starts_with($rawUri, $base)) {
                    $rawUri = substr($rawUri, strlen($base));
                }
                $uri = '/' . trim($rawUri, '/');
            }
        } else {
            $uri = '/' . trim($requestUri, '/');
        }

        if (empty($uri) || $uri === '//') {
            $uri = '/';
        }

        // Serve static assets if reached via front-controller rewriting
        if (str_starts_with($uri, '/assets/') || str_starts_with($uri, '/uploads/')) {
            $baseDir = defined('ROOT_PATH') ? ROOT_PATH : dirname(__DIR__);
            $targetAsset = $baseDir . str_replace('/', DIRECTORY_SEPARATOR, $uri);
            if (file_exists($targetAsset) && !is_dir($targetAsset)) {
                $ext = strtolower(pathinfo($targetAsset, PATHINFO_EXTENSION));
                $mimes = [
                    'css'   => 'text/css; charset=utf-8',
                    'js'    => 'application/javascript; charset=utf-8',
                    'json'  => 'application/json',
                    'svg'   => 'image/svg+xml',
                    'png'   => 'image/png',
                    'jpg'   => 'image/jpeg',
                    'jpeg'  => 'image/jpeg',
                    'webp'  => 'image/webp',
                    'ico'   => 'image/x-icon',
                    'woff'  => 'font/woff',
                    'woff2' => 'font/woff2',
                    'ttf'   => 'font/ttf',
                ];
                http_response_code(200);
                header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
                header('Content-Length: ' . (string)filesize($targetAsset));
                header('Cache-Control: public, max-age=86400');
                readfile($targetAsset);
                exit;
            }
        }

        // Match registered routes
        $routesForMethod = self::$routes[$method] ?? [];
        foreach ($routesForMethod as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameter matches
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                self::executeHandler($route['handler'], $params);
                return;
            }
        }

        // Not Found (404)
        if (self::$notFoundHandler !== null) {
            self::executeHandler(self::$notFoundHandler, []);
        } else {
            http_response_code(404);
            $notFoundView = APP_PATH . '/views/errors/404.php';
            if (file_exists($notFoundView)) {
                require $notFoundView;
            } else {
                echo '<h1 style="font-family:sans-serif;text-align:center;margin-top:100px;">404 — Artwork Not Found</h1>';
            }
        }
    }

    /**
     * Execute Controller Action or Callable Closure
     */
    private static function executeHandler(array|callable $handler, array $params): void {
        $reflection = null;
        $callable = null;

        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;

            if (!class_exists($controllerClass)) {
                throw new RuntimeException("Controller class {$controllerClass} not found");
            }

            $controller = new $controllerClass();
            if (!method_exists($controller, $method)) {
                throw new RuntimeException("Method {$method} does not exist in controller {$controllerClass}");
            }

            $reflection = new ReflectionMethod($controller, $method);
            $callable = [$controller, $method];
        } elseif (is_callable($handler)) {
            $reflection = is_object($handler) && !($handler instanceof Closure) 
                ? new ReflectionMethod($handler, '__invoke') 
                : new ReflectionFunction($handler);
            $callable = $handler;
        } else {
            return;
        }

        // Align and cast parameters to eliminate PHP 8 strict-type errors (e.g. string '1' -> int 1)
        $rawArgs = array_values($params);
        $finalArgs = [];

        if ($reflection !== null) {
            $refParams = $reflection->getParameters();
            foreach ($refParams as $index => $param) {
                if (isset($rawArgs[$index])) {
                    $val = $rawArgs[$index];
                    $type = $param->getType();

                    if ($type instanceof ReflectionNamedType) {
                        $typeName = $type->getName();
                        if ($typeName === 'int' && is_numeric($val)) {
                            $val = (int)$val;
                        } elseif ($typeName === 'float' && is_numeric($val)) {
                            $val = (float)$val;
                        } elseif ($typeName === 'bool') {
                            $val = filter_var($val, FILTER_VALIDATE_BOOLEAN);
                        } elseif ($typeName === 'string') {
                            $val = (string)$val;
                        }
                    } elseif ($type instanceof ReflectionUnionType) {
                        $types = array_map(fn($t) => $t->getName(), $type->getTypes());
                        if (in_array('int', $types, true) && is_numeric($val) && !in_array('string', $types, true)) {
                            $val = (int)$val;
                        }
                    }

                    $finalArgs[] = $val;
                } elseif ($param->isDefaultValueAvailable()) {
                    $finalArgs[] = $param->getDefaultValue();
                } else {
                    $finalArgs[] = null;
                }
            }

            // Append any leftover arguments if method accepts variadics
            if (count($rawArgs) > count($finalArgs)) {
                for ($i = count($finalArgs); $i < count($rawArgs); $i++) {
                    $val = $rawArgs[$i];
                    if (is_numeric($val) && (string)(int)$val === (string)$val) {
                        $val = (int)$val;
                    }
                    $finalArgs[] = $val;
                }
            }
        } else {
            $finalArgs = $rawArgs;
        }

        call_user_func_array($callable, $finalArgs);
    }
}

