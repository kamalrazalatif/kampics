<?php

// Sessions are files saved on server that contain user data like user id
// You access and update Session file on server using the sesison cookie PHPSESSID saved on users browser/ PC
// Sessions use a Session cookie but save data on server file rather than cookie file directly
// Accessing user data on session file is faster than accessing that data from database
// Only store certain User data liek Id on session file as other data for user may change on database,
// and not change immediately on session


// This is a class to help work with sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally
// inadvisable to store DB-related objects in sessions

// defining the Session class:
class Session {
    
    private $logged_in=false;
    public $user_id;
    public $message;
    
    function __construct(){
        session_start();
        $this->check_message();
        $this->check_login();
        
        /*if($this->logged_in){
            // action to take if user is logged in
        } else {
            // aciton to take if user is not logged in
        }*/
    }
    
    public function is_logged_in(){
        return $this->logged_in;
    }
    
    public function login($user){
        // database should find user based on username/ password
        if($user){
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->logged_in = TRUE;
        }
        
    }
    
    public function logout(){
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->logged_in = FALSE;
    }
    
    public function message($msg=""){
        if(!empty($msg)){
            // then this is "set message"
            $_SESSION['message'] = $msg;
        } else {
            // then this is "get message"
            return $this->message;
        }
        
    }
    
    private function check_login(){
        if(isset($_SESSION['user_id'])){
            $this->user_id = $_SESSION['user_id'];
            $this->logged_in = TRUE;
        } else {
            unset($this->user_id);
            $this->logged_in = FALSE;
        }
    } // end check_login method
    
    private function check_message(){
        // is there a message stored
        if(isset($_SESSION['message'])){
            // add it as an attribute and erase the stored version
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            $this->message = "";
        }
    }
}

$session = new Session();    // Instantiate an object of the Session class
$message = $session->message();

?>