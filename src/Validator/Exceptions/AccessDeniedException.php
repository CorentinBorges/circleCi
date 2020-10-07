<?php


namespace App\Validator\Exceptions;


class AccessDeniedException extends \Exception
{
    public function __construct($message, $code, \Exception $previous = null) {

        parent::__construct($message, 403, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "Une fonction personnalisée pour ce type d'exception\n";
    }
}