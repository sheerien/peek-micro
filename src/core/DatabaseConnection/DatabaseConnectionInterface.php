<?php
declare(strict_types = 1);
namespace Micro\Peek\DatabaseConnection;

interface DatabaseConnectionInterface
{
    /**
     * Create a new database connection.
     * 
     * @return \PDO
     */
    public function open():\PDO;
    
    /**
     * Close the database connection.
     * 
     * @return void
     */
    public function close():void;
}