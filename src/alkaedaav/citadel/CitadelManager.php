<?php

namespace alkaedaav\citadel;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\Config;

class CitadelManager {

    /** @var Citadel[] */
    protected static $citadel = [];

    /**
	 * @param String $citadelName
	 * @return bool
	 */
	public static function isCitadel(String $citadelName) : bool {
		if(isset(self::$citadel[$citadelName])){
			return true;
		}else{
			return false;
		}
		return false;
	}
	
	/**
	 * @param array $args
	 * @return void
	 */
	public static function createCitadel(Array $args = []) : void {
		self::$citadel[$args["name"]] = new Citadel($args["name"], $args["position1"], $args["position2"], $args["levelName"]);
	}
	
	/**
	 * @param String $citadelName
	 * @return void
	 */
	public static function removeCitadel(String $citadelName) : void {
		unset(self::$citadel[$citadelName]);
		$file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."citadel.yml", Config::YAML);
		$file->remove($citadelName);
		$file->save();
	}

	/**
	 * @param String $citadelName
	 * @return citadel
	 */
	public static function getCitadel(String $citadelName) : ?Citadel {
		return self::isCitadel($citadelName) ? self::$citadel[$citadelName] : null;
	}
	
	/**
	 * @return array[]
	 */
	public static function getCitadels() : array {
		return self::$citadel;
	}

	/**
	 * @return bool|String
	 */
	public static function CitadelIsEnabled(){
		$citadelData = false;
		foreach(array_values(self::getCitadels()) as $citadel){
			if($citadel->isEnable()){
				$citadelData .= $citadel->getName(); //object
			}
		}
		return $citadelData ?? false;
	}
}

?>