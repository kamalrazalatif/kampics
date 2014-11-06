<?php

require_once(LIB_PATH.DS."database.php");  // database class/ object $db

class User extends DatabaseObject {
    
    protected static $table_name = "users";
    protected static $db_fields = array('id', 'username', 'hashed_password', 'first_name', 'last_name','profpic','bio');
    
    public $id;
    public $username;
    public $hashed_password;
    public $first_name;
    public $last_name;
	public $profpic;
	public $bio;
    
    public function full_name(){
        if(isset($this->first_name)&& isset($this->last_name)){
            return $this->first_name . " " . $this->last_name;
        } else {
            return "";
        }
    }
    
    
    public static function authenticate($username="", $password=""){
        global $db;
        $username = $db->mysql_prep($username);
        $hashed_password = sha1($db->mysql_prep($password));
        
        
        $sql = "SELECT * FROM users ";
        $sql .= "WHERE username = '{$username}' ";
        $sql .= "AND hashed_password = '{$hashed_password}' ";
        $sql .= "LIMIT 1";
        
        $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array): false;
    }
    
    
    // Common Database Methods
    
    public static function find_all(){
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }
    
  
    public static function find_by_id($id=0){
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array):false;
    }
    
    public static function find_by_sql($sql=""){
        global $db;
        $result_set = $db->query($sql);
        $object_array = array();  // each record_set row will become an object living inside an
                                    // array element in the object_array
        while($row = $db->fetch_assoc($result_set)){
            // this creates an object for each user/ db row and
            // inserts this object into the $object_array in a new index element
            $object_array[] = self::instantiate($row); 
        }
        return $object_array;
    }
    
    public static function count_all() {
	global $db;
	$sql = "SELECT COUNT(*) FROM ".self::$table_name;
	$result_set = $db->query($sql);
	$row = $db->fetch_assoc($result_set);
	return array_shift($row);
    } // end count_all method
    
    private static function instantiate($record){
        // could check that $record exists and is an array
        // this is simple long form approach
        
        $object = new self;  // Instantiate an object instance of the User class
        
        // updating this specific user object with data for this user
        //$object->userId = $record['userId'];  
        //$object->username = $record['username'];
        //$object->hashed_password = $record['hashed_password'];
        //$object->firstname = $record['firstname'];
        //$object->lastname = $record['lastname'];
        
        // more dynamic short-form approach,
        // using loop - attribute name must be same as db fieldname:
        foreach($record as $attribute=>$value){
            if($object->has_attribute($attribute)){
                $object->$attribute = $value;
            }
        }
        
        return $object;
    }
    
    private function has_attribute($attribute) {
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $this->attributes());
	}
    
    
    protected function attributes() { 
		// return an array of attribute names and their values
	  $attributes = array();
	  foreach(self::$db_fields as $field) {
	    if(property_exists($this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
	}
	
    protected function sanitized_attributes() {
      global $db;
      $clean_attributes = array();
      // sanitize the values before submitting
      // Note: does not alter the actual value of each attribute
      foreach($this->attributes() as $key => $value){
        $clean_attributes[$key] = $db->mysql_prep($value);
      }
      return $clean_attributes;
    }
    
    
    
    // C.R.U.D. Instance Methods - need to instantiate object instance to run these
    
    public function save(){
        // A new record won't have an id yet
        return isset($this->id) ? $this->update() : $this->create();
    }
    
    public function create() {
		global $db;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
	  $sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
	  $sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
	  if($db->query($sql)) {
	    $this->id = $db->insert_id();
	    return true;
	  } else {
	    return false;
	  }
    }
    
    public function update() {
	  global $db;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $db->mysql_prep($this->id);
                
	  $db->query($sql);
	  return ($db->affected_rows() == 1) ? true : false;
    }
    
    public function delete(){
        global $db;
        // Don't forget your SQL syntax and good habits:
        // - DELETE FROM table WHERE condition LIMIT 1
        // - escape all values to prevent SQL injection
        // - use LIMIT 1
        
        $sql = "DELETE FROM ". self::$table_name;
        $sql .= " WHERE id=" . $db->mysql_prep($this->id);
        $sql .= " LIMIT 1";
        
        $db->query($sql);
        return ($db->affected_rows()==1) ? true:false;
    
    
    // NB: After deleting, the instance of User still 
		// exists, even though the database entry does not.
		// This can be useful, as in:
		//   echo $user->first_name . " was deleted";
		// but, for example, we can't call $user->update() 
		// after calling $user->delete().
        
    }
    
    
    
    
    
    
} // end of User class

?>