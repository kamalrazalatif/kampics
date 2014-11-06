<?php

require_once(LIB_PATH.DS."database.php");  // database class/ object $db

class ProfPic { 
    
    public $filename;
    public $type;
    public $size;
    
    private $temp_path;
    protected $upload_dir = "images/profpic";
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
            // make sure there are no errors
            // can't save if there are pre-existing errors
            if(!empty($this->errors)){
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
			return $this->filename;
	    } else {
		// failure
		// file was not moved
		$this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder";
		return false;
	    } 
            
        
    } // end of method save()
    
    
   
    public function image_path(){
	return $this->upload_dir.DS.$this->filename;
    }  // end method image_path()
    
   

    
    
} // end class profPic



?>