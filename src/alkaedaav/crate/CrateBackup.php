<?php

namespace alkaedaav\crate;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\item\Items;

use pocketmine\utils\{Config, TextFormat as TE};

class CrateBackup {

    /**
     * @return void
     */
    public static function initAll() : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."crates.yml", Config::YAML);
        foreach($file->getAll() as $name => $values){
            self::init($name);
        }
    }

    /**
     * @return void
     */
    public static function saveAll() : void {
        foreach(CrateManager::getCrates() as $name => $values){
            self::save($name);
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function init(String $name) : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."crates.yml", Config::YAML);
            $crateData = $file->getAll()[$name];

            if(isset($crateData["contents"])){
                foreach($crateData["contents"] as $slot => $item){
                    $crateData["contents"][$slot] = Items::itemDeserialize($item);
                }
            }
            CrateManager::createCrate($crateData);
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load crate: ".$name);  
            Loader::getInstance()->getLogger()->error($exception->getMessage()); 
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function save(String $name) : void {
        try {
            $crateData = [];

            $crate = CrateManager::getCrate($name);
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."crates.yml", Config::YAML);

            $crateData["name"] = $crate->getName();
            $crateData["block_id"] = $crate->getBlock();
            $crateData["key_id"] = $crate->getKey();
            $crateData["keyName"] = $crate->getKeyName();
            $crateData["nameFormat"] = $crate->getNameFormat();
            $crateData["position"] = $crate->getPosition();
            $crateData["particles"] = $crate->getParticles();

            foreach($crate->getItems() as $slot => $item){
                $crateData["contents"][$slot] = Items::itemSerialize($item);
            }
            $file->set($crate->getName(), $crateData);
            $file->save();
        } catch (\Exception $exception){
            Loader::getInstance()->getLogger()->error("Can't save crate: ".$name);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}

?>