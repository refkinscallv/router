<?php

    namespace RF\Router;

    use RF\Router\RouteCollect;
    use RF\Router\RouteExec;
    use RF\Router\RouteException;

    class Route {

        private static array $storage = [];
        private static string $prefix = '';
        private static string $errMessage = 'Router an Error Occurred';

        public static function register(array $args = []): void {
            if (!is_array($args)) {
                throw new RouteException(self::$errMessage, 1, null, "'register' arguments aren't array");
            }
            
            if(!empty($args)) {
                foreach($args as $file) {
                    require $file .".php";
                }
            }
        }

        public static function setMaintenance($classOrFunc) {
            if (is_callable($classOrFunc) || is_array($classOrFunc)) {
                self::$storage['maintenance'] = ['callback' => $classOrFunc];
            }
        }

        public static function set404($classOrFunc) {
            if (is_callable($classOrFunc) || is_array($classOrFunc)) {
                self::$storage['404'] = ['callback' => $classOrFunc];
            }
        }

        public static function group(array $attr, callable $callback) {
            $previousPrefix = self::$prefix;
            self::$prefix = $previousPrefix . ($attr['prefix'] ?? '');

            $callback();

            self::$prefix = $previousPrefix;
        }

        private static function applyPrefix(string $path): string {
            return self::$prefix . $path;
        }

        public static function set(string $path, $classOrFunc, string $method = 'GET'): void {
            $path = self::applyPrefix($path);
            self::$storage['routes'][$path] = [
                'callback' => $classOrFunc,
                'method' => $method
            ];
        }

        public static function get(string $path, $classOrFunc): void {
            self::set($path, $classOrFunc, 'GET');
        }

        public static function post(string $path, $classOrFunc): void {
            self::set($path, $classOrFunc, 'POST');
        }

        public static function put(string $path, $classOrFunc): void {
            self::set($path, $classOrFunc, 'PUT');
        }

        public static function delete(string $path, $classOrFunc): void {
            self::set($path, $classOrFunc, 'DELETE');
        }

        public static function patch(string $path, $classOrFunc): void {
            self::set($path, $classOrFunc, 'PATCH');
        }

        public static function options(string $path, $classOrFunc): void {
            self::set($path, $classOrFunc, 'OPTIONS');
        }

        public static function run(): void {
            RouteCollect::init(self::$storage, function($routes) {
                RouteExec::exec($routes);
            });
        }

    }
