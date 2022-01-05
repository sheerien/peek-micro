<?php
declare(strict_types = 1);
namespace Micro\Peek\DatabaseConnection;

use Micro\Peek\DatabaseConnection\Exception\DatabaseConnectionException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    /**
     * @var \PDO
     */
    protected \PDO $dbh;
    
    /**
     * @var array
     */
    protected array $credentials;
    
    /**
     * Main Constructor class
     * 
     * @return void
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }
    
	/**
	 * Create a new database connection.
	 *
	 * @return \PDO
	 */
	public function open(): \PDO 
    {
        $params = [
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        try{
            $this->dbh = new \PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );
        }catch(\PDOException $exception){
            throw new DatabaseConnectionException($exception->getMessage(), $exception->getCode());
        }
	}
	
	/**
	 * Close the database connection.
	 */
	public function close(): void 
    {
        
	}
}