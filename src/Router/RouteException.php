<?php

    namespace RF\Router;

    use Exception;

    class RouteException extends Exception {

        private $details;

        public function __construct($message = "", $code = 0, Exception $previous = null, $details = "") {
            parent::__construct($message, $code, $previous);
            $this->details = $details;
        }

        public function getDetails() {
            return $this->details;
        }

        public function __toString() {
            return __CLASS__ ." [{$this->code}: {$this->message} - Details : {$this->details}\n]";
        }
        
    }
