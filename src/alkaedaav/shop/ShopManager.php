<?php

namespace alkaedaav\shop;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\Config;

class ShopManager {

    /** @var Array[] */
    public static $shop = [];

    /**
     * @param String $position
     * @return bool
     */
    public static function isShop(String $position) : bool {
        if(isset(self::$shop[$position])){
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
    public static function createShop(Array $args = []) : void {
        self::$shop[$args["position"]] = new Shop($args["shop_type"], $args["shop_id"], $args["shop_damage"], $args["shop_amount"], $args["shop_price"], $args["position"]);
    }

    /**
     * @param String $position
     * @return void
     */
    public static function removeShop(String $position) : void {
        unset(self::$shop[$position]);
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."shops.yml", Config::YAML);
        $file->remove($position);
        $file->save();
    }

    /**
     * @param String $position
     * @return Shop
     */
    public static function getShop(String $position) : ?Shop {
        return self::isShop($position) ? self::$shop[$position] : null;
    }

    /**
     * @return Array[]
     */
    public static function getShops() : Array {
        return self::$shop;
    }
}