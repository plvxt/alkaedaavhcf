<?php

namespace alkaedaav\player;

use alkaedaav\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Server;

class PlayerBase {

    /**
	 * @param Player $player
	 * @return void
	 */
	public static function create(String $playerName) : void {
		if(!file_exists(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml")){
		    $config = new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
            $config->setAll(["kills" => null, "lives" => null, "lives_claimed" => false, "reclaim" => null, "koth_host" => null, "brewer" => null, "balance" => null]);
            $config->save();
		}
	}

	/**
	 * @param Player $player
	 * @return void
	 */
	public static function remove(Player $player) : void {
		if(file_exists(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml")){
			unlink(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml");
		}//finished
	}
	
	/**
	 * @param String $playerName
	 * @param String $data
	 * @return void
	 */
	public static function removeData(String $playerName, String $data) : void {
		if(!file_exists(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml")) return;
		
		$config = new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
		$config->remove($data);
		$config->save();
	}
	
	/**
	 * @param String $playerName
	 * @param String $data
	 * @return bool
	 */
	public static function isData(String $playerName, String $data) : bool {
		if(!file_exists(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml")) return false;
		
		$config = new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
		if($config->exists($data)){
			return true;
		}else{
			return false;
		}
		return false;
	}
	
	/**
	 * @param String $playerName
	 * @return Config|null
	 */
	public static function getData(String $playerName){
		if(!file_exists(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml")) return;
		
		return new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
	}

	/**
	 * @param String $playerName
	 * @param String $data
	 * @param String $value
	 * @return void
	 */
	public static function setData(String $playerName, String $data, $value) : void {
		if(!file_exists(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml")) return;
		
		$config = new Config(Loader::getInstance()->getDataFolder()."players".DIRECTORY_SEPARATOR."{$playerName}.yml", Config::YAML);
		$config->set($data, $value);
		$config->save();
	}

	/**
	 * @param String $playerName
	 * @return Int
	 */
	public static function getKills(String $playerName) : Int {
		return self::getData($playerName)->get("kills") === null ? 0 : self::getData($playerName)->get("kills");
	}
}

?>