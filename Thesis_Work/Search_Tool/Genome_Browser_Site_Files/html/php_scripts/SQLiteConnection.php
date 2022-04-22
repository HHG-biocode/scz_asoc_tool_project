<?php
ini_set('display_errors', 1);
include 'config.php';

class SQLiteConnection {
    public $pdo;
    public function connect() {
	if ($this->pdo ==null){ 
        $this->pdo = new PDO("sqlite:".Config::SQLITE_FILEPATH);
    }
	return $this->pdo;
    }   
}
?>