<?php

namespace alkaedaav\koth;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\Config;

class KothManager {

    /** @var Array[] */
    protected static $koth = [];

    /**
	 * @param String $kothName
	 * @return bool
	 */
	public static function isKoth(String $kothName) : bool {
		if(isset(self::$koth[$kothName])){
			return true;
		}else{
			return false;
		}
		return false;
	}
	
	/**
	 * @param Array $args
	 * @return void
	 */
	public static function createKoth(Array $args = []) : void {
		self::$koth[$args["name"]] = new Koth($args["name"], $args["position1"], $args["position2"], $args["levelName"]);
	}
	
	/**
	 * @param String $kothName
	 * @return void
	 */
	public static function removeKoth(String $kothName) : void {
		unset(self::$koth[$kothName]);
		$file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."koths.yml", Config::YAML);
		$file->remove($kothName);
		$file->save();
	}

	/**
	 * @param String $kothName
	 * @return koth
	 */
	public static function getKoth(String $kothName) : ?Koth {
		return self::iskoth($kothName) ? self::$koth[$kothName] : null;
	}
	
	/**
	 * @return Array[]
	 */
	public static function getKoths() : Array {
		return self::$koth;
	}

	/**
	 * @return bool|String
	 */
	public static function kothIsEnabled(){
		$kothData = false;
		foreach(array_values(self::getKoths()) as $koth){
			if($koth->isEnable()){
				$kothData .= $koth->getName(); //object
			}
		}
		return $kothData ?? false;
	}
}

?>