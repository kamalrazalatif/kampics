<?php
require_once(LIB_PATH.DS."config.php");  // Database connection details as constants

class MySQLDatabase {
    
    private $connection;
    // public $last_query; not sure how useful this is in production
    
    function __construct(){
        $this->open_connection();
    }
    
    public function open_connection(){
        $this->connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
        if(!$this->connection){
            die("Database Connection Failed " . mysqli_error());
        }
    }
    
    
    public function close_connection(){
        if(isset($this->connection)){
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }
    
    public function query($sql){
        // $this->last_query = $sql; 
        $result = mysqli_query($this->connection,$sql);
        $this->confirm_query($result);
        return $result;
    }
    
    // database neutral methods
    public function fetch_assoc($result){
        return mysqli_fetch_assoc($result);
    }
    
    public function num_rows($result){
        return mysqli_num_rows($result);
    }
    
    public function insert_id(){
        // get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }
    
    public function affected_rows(){
        return mysqli_affected_rows($this->connection);
    }
    
    
    // Escape User input text data
    public function mysql_prep($string){
        $escaped_string = mysqli_real_escape_string($this->connection,$string);
        return $escaped_string;
    }
          
    
    private function confirm_query($result) {
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}
    }
    
}


// create an instance of the Database Class/ Object
$db = new MySQLDatabase();

?>