<?php
declare(strict_types = 1);
namespace Micro\Peek\DatabaseConnection\Exception;

class DatabaseConnectionException extends \PDOException
{
    public $message;
    public $code;
    public function __construct($message = null, $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($this->message, $this->code);
    }
}