<?php

namespace alkaedaav\utils;

class Time {
	
	/**
	 * @param mixed $time
	 * @return String
	 */
	public static function getTimeToString($time) : String {
		return gmdate("i:s", $time);
	}
	
	/**
	 * @param mixed $time
	 * @return String
	 */
	public static function getTimeToFullString($time) : String {
		return gmdate("H:i:s", $time);
	}
    
    /**
     * @param mixed $time
     * @return String
     */
    public static function getTime($time) : String {
		$remaning = $time - time();
		$s = $remaning % 60;	
	
		$m = null;		
		$h = null;		
		$d = null;
		
		if($remaning >= 60){			
			$m = floor(($remaning % 3600) / 60);		
			if($remaning >= 3600){				
				$h = floor(($remaning % 86400) / 3600);				
				if($remaning >= 3600 * 24){					
					$d = floor($remaning / 86400);					
				}			
			}		
		}		
		return ($m !== null ? ($h !== null ? ($d !== null ? "$d days " : "")."$h hours " : "")."$m minutes " : "")."$s seconds";
	}
}

?>