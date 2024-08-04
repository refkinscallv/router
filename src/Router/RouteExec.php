<?php

    namespace RF\Router;

    use RF\Router\RouteException;

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

            if(!isset($routes['routes'])) {
                throw new RouteException(self::$errMessage, 1, null, "Undefined array key 'routes'");
            }

            foreach ($routes['routes'] as $path => $route) {
                $params = [];
                if (self::matchRoute($path, $requestUri, $params)) {
                    if ($route['method'] === $requestMethod) {
                        self::callFunction($route['callback'], $params);
                        return;
                    }
                }
            }

            if (isset($routes['404'])) {
                self::callFunction($routes['404']['callback']);
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "404 Not Found";
            }
        }

        private static function matchRoute(string $routePath, string $requestUri, array &$params): bool {
            $routeParts = explode('/', trim($routePath, '/'));
            $uriParts = explode('/', trim($requestUri, '/'));

            if (count($routeParts) < count($uriParts)) {
                return false;
            }

            $params = [];
            foreach ($routeParts as $index => $part) {
                if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                    if (isset($uriParts[$index])) {
                        $params[] = self::sanitize($uriParts[$index]);
                    } elseif (strpos($part, '?}') !== false) {
                        $params[] = '';
                    } else {
                        return false;
                    }
                } elseif ($part !== $uriParts[$index]) {
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
                throw new \Exception(self::$errMessage);
            }
        }

        private static function sanitize(string $input): string {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
        
    }
