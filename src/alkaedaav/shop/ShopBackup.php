<?php

namespace alkaedaav\shop;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\item\Items;

use pocketmine\utils\{Config, TextFormat as TE};
use alkaedaav\shop\ShopManager;

class ShopBackup {

    /**
     * @return void
     */
    public static function initAll() : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."shops.yml", Config::YAML);
        foreach($file->getAll() as $position => $values){
            self::init($position);
        }
    }

    /**
     * @return void
     */
    public static function saveAll() : void {
        foreach(ShopManager::getShops() as $position => $values){
            self::save($position);
        }
    }

    /**
     * @param String $type
     * @return void
     */
    public static function init(String $type) : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."shops.yml", Config::YAML);
            $shopData = $file->getAll()[$type];

            ShopManager::createShop($shopData);
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load shop: ".$type);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }

    /**
     * @param String $type
     * @return void
     */
    public static function save(String $type) : void {
        try {
            $shopData = [];

            $shop = ShopManager::getShop($type);
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."shops.yml", Config::YAML);

            $shopData["position"] = $shop->getPosition();
            $shopData["shop_type"] = $shop->getType();
            $shopData["shop_id"] = $shop->getId();
            $shopData["shop_damage"] = $shop->getDamage();
            $shopData["shop_amount"] = $shop->getAmount();
            $shopData["shop_price"] = $shop->getPrice();

            $file->set($shop->getPosition(), $shopData);
            $file->save();

        } catch (\Exception $exception){
            Loader::getInstance()->getLogger()->error("Can't save shop: ".$type);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}

?>