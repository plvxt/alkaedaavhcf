<?php

namespace alkaedaav\packages;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\item\Items;

use pocketmine\utils\{Config, TextFormat as TE};

class PackageBackup {

    /**
     * @return void
     */
    public static function initAll() : void {
        self::init();
    }

    /**
     * @return void
     */
    public static function saveAll() : void {
        self::save();
    }

    /**
     * @return void
     */
    public static function init() : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."packages.yml", Config::YAML);
            if(empty($file->getAll())) return;
            $packageData = $file->getAll()["items"];

            if(isset($packageData["contents"])){
                foreach($packageData["contents"] as $slot => $item){
                    $packageData["contents"][$slot] = Items::itemDeserialize($item);
                }
            }
            PackageManager::createPackage($packageData);
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load package");  
            Loader::getInstance()->getLogger()->error($exception->getMessage()); 
        }
    }

    /**
     * @return void
     */
    public static function save() : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."packages.yml", Config::YAML);
            $package = PackageManager::getPackage();
            if(empty($package)) return;

            $packageData = [];
            foreach($package->getItems() as $slot => $item){
                $packageData["contents"][$slot] = Items::itemSerialize($item);
            }
            $file->set("items", $packageData);
            $file->save();
        } catch (\Exception $exception){
            Loader::getInstance()->getLogger()->error("Can't save package");
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}

?>