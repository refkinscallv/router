<?php

    namespace RF\Router;

    class RouteExec {

        private static array $routes = [];
        private static string $errMessage = 'Router an Error Occurred';

        public static function exec(array $routes): void {
            self::$routes = $routes;

            if (isset($routes['maintenance'])) {
                self::callFunction($routes['maintenance']['callback']);
                return;
            }

            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $requestMethod = $_SERVER['REQUEST_METHOD'];

            foreach ($routes['routes'] as $path => $route) {
                $params = [];
                if (self::matchRoute($path, $requestUri, $params)) {
                    if ($route['method'] === $requestMethod) {
                        self::callFunction($route['callback'], $params);
                        return;
                    }
                }
            }

            if (isset($routes['default'])) {
                self::callFunction($routes['default']['callback']);
            } elseif (isset($routes['404'])) {
                self::callFunction($routes['404']['callback']);
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "404 Not Found";
            }
        }

        private static function matchRoute(string $routePath, string $requestUri, array &$params): bool {
            $routeParts = explode('/', trim($routePath, '/'));
            $uriParts = explode('/', trim($requestUri, '/'));

            if (count($routeParts) > count($uriParts)) {
                return false;
            }

            $params = [];
            foreach ($routeParts as $index => $part) {
                if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                    $params[] = $uriParts[$index] ?? '';
                } elseif ($part !== ($uriParts[$index])) {
                    return false;
                }
            }

            return true;
        }

        private static function callFunction($callback, array $params = []): void {
            if (is_callable($callback)) {
                call_user_func_array($callback, $params);
            } elseif (is_array($callback) && class_exists($callback[0]) && method_exists($callback[0], $callback[1])) {
                call_user_func_array([new $callback[0], $callback[1]], $params);
            } else {
                throw new RouteException(self::$errMessage, 1, null, "Invalid callback function or method");
            }
        }
        
    }
