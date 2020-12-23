<?php
class Err{
    public $code;
    public $err_msg;
    public $print;
    private $time; 
    private $user; 
    private $error; 
    private $log = 'errors/error.log.txt';     
    public function __construct($status = null){ 
       $this->code = $status;   
       $this->time = new DateTime(null, new DateTimeZone('Africa/Nairobi')); # I am from nairobi but you can chage it to your own timezone 
   }
   public function _set_log($file){
      $this->log = $file; 
   }

   public function err($user, $err = null){      
   	   $this->code == null ? $this->code = $err:''; 
       $this->user = $user;    	
       $status = $this->code ;  
       $error_array = [
       	'wrong information given',//0
       	'user does not exist',//1
       	'empty fields',//2 
       	'post does not exist',//3
       	'username already exist',//4
       	'can not send message',//5
       	'wrong password',//6
       	'you are not autorised',//7
       	'missing parameters in request',//8
       	'key parameter should be boolean',//9
       	'invalid value in parameter',//10
       	'illigal action performed',//11
       	'already liked this',//12 
       	'already followed this user',//13
       	'unknown request'//14 
       ];
       $this->error = $error_array[$status];
       $this->print = print_r( 
             json_encode(  
                array(
                    'code'=> $status,  
                    'msg'=> $this->error,
                    'type'=>'error'
                )
             ) 
       ); 
       $this->_log();  
       return $this->print; 
   }
   private function _log(){	  
   	$txt = file_get_contents($this->log);  
   	$txt .= "user: ".$this->user." error occurred Error: ".$this->error."    ".$this->time->format("Y-m-d H:i:s")."  on  ".$_SERVER['SCRIPT_FILENAME']."\n";   
   	file_put_contents($this->log, $txt);  
   }
}   