<?php

class Mod_Time extends System {

	public function __construct() {


	}


	public function time_passed($time){

		$timestring_hours = '';
		$timestring_minutes = '';
		$time = time()-$time;
		$hours = (($time%604800)%86400)/3600;
		$minutes = ((($time%604800)%86400)%3600)/60;
		if(floor($hours)) $timestring_hours .= floor($hours)." hours ";
		if(floor($minutes)) $timestring_minutes .= floor($minutes)." minutes ";
		$timestring = array('hours'=>$timestring_hours, 'minutes'=>$timestring_minutes);
		return $timestring;
	}

	public function start($location){
$this->start_time = 0;
	   $this->start_time = explode(" ", microtime());
	   $this->start_time = $this->start_time[1] + $this->start_time[0];
           $this->location = $location;

	}

	public function finish(){
$this->finish_time = 0;
	   $this->finish_time = explode(" ", microtime());
	   $this->finish_time = $this->finish_time[1] + $this->finish_time[0];
	   echo  $this->location . ': ' . $this->finish_time -  $this->start_time. '<br />';
	}

	public function script_speed(){

		$this->execution_time =  $this->finish_time -  $this->start_time;

	}

	public function server_time($timestamp=false){

		if(!$timestamp){

			$time_data =  getdate(time());

		}else{

			$time_data =  getdate($timestamp);

		}

		return $time_data;
	}

	public function date_formating(){


	}

	public function time_formating(){


	}

	public function calendar(){


	}

	public function clock(){


	}

	public function counter(){


	}
}