<?php

if( !defined("ABSPATH") )
{
    http_response_code(404);
    exit;
}

class DBDriver {
    private $pdo;
    
    public function connect($_host, $_user, $_pass, $_dbname)
    {
        try {
            $this->pdo = new PDO("mysql:host=$_host;dbname=$_dbname;charset=utf8", $_user, $_pass);
        } catch (Exception $e) {
            //trigger_error("Cannot connect to MySQL: ".$ex->getMessage(), E_USER_ERROR);
            return $e;
        }
        return true;
    }
    
    public function query( $q ) {
        return $this->pdo->query( $q );
    }
    
    public function lastInsertedId() {
        return $this->pdo->lastInsertId();
    }
    
    public function prepare( $q ) {
        return $this->pdo->prepare($q);
    }

    public function close() {
        $this->pdo = null;
    }
}
