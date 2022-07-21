<?php

namespace alkaedaav\citadel;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\utils\Translator;

use pocketmine\utils\{Config, TextFormat as TE};

class CitadelBackup {

    /**
     * @return void
     */
    public static function initAll() : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."citadel.yml", Config::YAML);
        foreach($file->getAll() as $name => $values){
            self::init($name);
        }
    }

    /**
     * @return void
     */
    public static function saveAll() : void {
        foreach(CitadelManager::getCitadels() as $name => $values){
            self::save($name);
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function init(String $name) : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."citadel.yml", Config::YAML);
            $citadelData = $file->getAll()[$name];

            CitadelManager::createCitadel($citadelData);
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load citadel: ".$name);  
            Loader::getInstance()->getLogger()->error($exception->getMessage()); 
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function save(String $name) : void {
        try {
            $citadelData = [];

            $citadel = CitadelManager::getCitadel($name);
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."citadel.yml", Config::YAML);

            $citadelData["name"] = $citadel->getName();
            $citadelData["levelName"] = $citadel->getLevel();
            $citadelData["position1"] = Translator::vector3ToArray($citadel->getPosition1());
            $citadelData["position2"] = Translator::vector3ToArray($citadel->getPosition2());

            $file->set($citadel->getName(), $citadelData);
            $file->save();
        } catch (\Exception $exception){
            Loader::getInstance()->getLogger()->error("Can't save citadel: ".$name);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}

?>