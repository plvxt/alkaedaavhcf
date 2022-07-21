<?php

namespace alkaedaav\koth;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\utils\Translator;

use pocketmine\utils\{Config, TextFormat as TE};

class KothBackup {

    /**
     * @return void
     */
    public static function initAll() : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."koths.yml", Config::YAML);
        foreach($file->getAll() as $name => $values){
            self::init($name);
        }
    }

    /**
     * @return void
     */
    public static function saveAll() : void {
        foreach(KothManager::getKoths() as $name => $values){
            self::save($name);
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function init(String $name) : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."koths.yml", Config::YAML);
            $kothData = $file->getAll()[$name];

            KothManager::createKoth($kothData);
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load koth: ".$name);  
            Loader::getInstance()->getLogger()->error($exception->getMessage()); 
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function save(String $name) : void {
        try {
            $kothData = [];

            $koth = KothManager::getKoth($name);
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."koths.yml", Config::YAML);

            $kothData["name"] = $koth->getName();
            $kothData["levelName"] = $koth->getLevel();
            $kothData["position1"] = Translator::vector3ToArray($koth->getPosition1());
            $kothData["position2"] = Translator::vector3ToArray($koth->getPosition2());

            $file->set($koth->getName(), $kothData);
            $file->save();
        } catch (\Exception $exception){
            Loader::getInstance()->getLogger()->error("Can't save koth: ".$name);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}

?>