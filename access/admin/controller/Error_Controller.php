<?php

class Error_pages_Controller extends Controller {
	
	protected function __construct() {

		parent::__construct();
	}
	
	public function home($error_code){
		
		if(ERROR_PAGES){
			$error_message = $this->PublicModel_Error_pages_Model->error_message($error_code);
			
			echo '<hr /><br />';
			echo 'Ovde ide poziv View za template';			
			echo '<hr /><br />';
			echo $error_message['message'];
			echo '<br />';
			echo '<br />';
			echo $error_message['explanation'];
			
		}else{
			
			$this->search_page();
			
		}
	}	
	
	public function search_page(){
		
		echo 'Put here search page';
		
	}

}	
