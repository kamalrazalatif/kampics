<?php

require_once(LIB_PATH.DS."database.php");  // database class/ object $db

class Photograph extends DatabaseObject { // when Late Static Bindings not an issue can dump C.R.U.D methods into DatabaseObject Class
    
    protected static $table_name = "photographs";
    protected static $db_fields = array('id','user_id','album_id','filename','type','size','caption');
    
    public $id;
	public $user_id;
	public $album_id;
    public $filename;
    public $type;
    public $size;
    public $caption;
    
    private $temp_path;
    protected $upload_dir = "images";
    public $errors = array();
    
    protected $upload_errors = array(
	// http://www.php.net/manual/en/features.file-upload.errors.php
	UPLOAD_ERR_OK 		=> "No errors.",
	UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL 	=> "Partial upload.",
        UPLOAD_ERR_NO_FILE 	=> "No file.",
        UPLOAD_ERR_NO_TMP_DIR   => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE   => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
    );
    
    
    // Pass in $_FILE['uploaded_file'] as an argument
    public function attach_file($file){
        // Perform error checking on the form parameters
        if(!$file || empty($file) || !is_array($file)){
            // nothing uploaded or wrong argument usage
            $this->errors[] = "No file was uploaded";
            return false;
        } elseif ($file['error'] != 0) {
            // error: report what PHP says was wrong
            $this->errors[] = $this->upload_errors[$file['error']];
            return false;
        } else {
            // instantiate an object for the photo uploaded & set this object's attributes to the form parameters
            $this->temp_path = $file['tmp_name'];
            $this->filename = basename($file['name']);
            $this->type = $file['type'];
            $this->size = $file['size'];
            
            // Don't worry about saving anythign to the Database yet
            return true;
        }  

    }
    
    public function save(){
        // A new record won't have an id yet
        if(isset($this->id)){
            // really just to update the caption
            $this->update();
        } else {
            // make sure there are no errors
            
            // can't save if there are pre-existing errors
            if(!empty($this->errors)){
                return false;
            }
            
            // make sure the caption is nto too long for the db
            if(strlen($this->caption) > 255){
                $this->errors[] = "The caption can onyl be 255 characters long.";
                return false;
            }
	    
	    // can't save withotu the filename and the temp location
	    if(empty($this->filename) || empty($this->temp_path)){
		$this->errors[] = "The file location was not available";
		return false;
	    }
	    
	    // If no errors - Determien the target path:
	    $target_path = SITE_ROOT . DS . $this->upload_dir . DS . $this->filename;
	    
	    // Make sure a file doesn't already exist in the file location
	    if(file_exists($target_path)){
		$this->errors[] = "The file {$this->filename} already exists.";
		return false;
	    }
	    
            
            
            // Attempt to move the file
	    if(move_uploaded_file($this->temp_path,$target_path)){
		// success
		// Save a corresponding entry to the Database
		if($this->create()){
		    // we are done with temp path, the file isn't there anymore
		    unset($this->temp_path);
		    return true;
		}
	    } else {
		// failure
		// file was not moved
		$this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder";
		return false;
	    } 
            
        }
    } // end of method save()
    
    
    public function destroy(){
	// 1. first remove the database record for the photo
	if($this->delete()){
	    // 2. then remove the file
	    $target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
	    return unlink($target_path) ? true:false;
	} else {
	    // database delete failed
	    return false;
	}
    } // end method destroy()
    
    public function image_path(){
	return $this->upload_dir.DS.$this->filename;
    }  // end method image_path()
    
    public function size_as_text() {
	    if($this->size < 1024) {
		    return "{$this->size} bytes";
	    } elseif($this->size < 1048576) {
		    $size_kb = round($this->size/1024);
		    return "{$size_kb} KB";
	    } else {
		    $size_mb = round($this->size/1048576, 1);
		    return "{$size_mb} MB";
	    }
    } // end method size_as_test()
    
    public function comments(){ // runs SQL select qry to get all comments for this photo object
	return Comment::find_comments_on($this->id);
    }
    
    // Common Database Methods
    // when Late Static Bindings not an issue can dump all these C.R.U.D methods into DatabaseObject Class
    public static function find_all(){
        return self::find_by_sql("SELECT * FROM " . self::$table_name);
    }
    
  
    public static function find_by_id($id=0){
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id=".$db->mysql_prep($id)." LIMIT 1");
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
    } // end find_by_sql method
    
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
    
    
    // replaced with a custom save() for fiel uploads - above
    /*public function save(){
        // A new record won't have an id yet
        return isset($this->id) ? $this->update() : $this->create();
    }*/
    
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
}



?>