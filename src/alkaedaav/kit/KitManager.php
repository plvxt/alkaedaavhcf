<?php

namespace alkaedaav\kit;

use alkaedaav\Loader;
use pocketmine\utils\Config;

class KitManager
{

    /** @var Kit[] */
    public static array $kits = [];

    /**
     * @param string $kitName
     * @return bool
     */
    public static function isKit(string $kitName): bool
    {
        return isset(self::$kits[$kitName]);
    }

    /**
     * @param array $args
     * @param bool $addMenu
     */
    public static function createKit(array $args, bool $addMenu = true): void
    {
        self::$kits[$args["name"]] = new Kit($args["name"], !empty($args["contents"]) ? $args["contents"] : [], !empty($args["armorContents"]) ? $args["armorContents"] : [], $args["permission"], $args["nameFormat"], $args['representativeItem']);
        
        if ($addMenu) {
            $kit_menu = new Config(Loader::getInstance()->getDataFolder() . 'backup/kit_menu.json', Config::JSON);
            $data = $kit_menu->getAll();
            $data[] = $args['name'];
            $kit_menu->setAll($data);
            $kit_menu->save();
        }
    }

    /**
     * @param string $kitName
     */
    public static function removeKit(string $kitName): void
    {
        unset(self::$kits[$kitName]);
        $file = new Config(Loader::getInstance()->getDataFolder() . "backup" . DIRECTORY_SEPARATOR . "kits.yml", Config::YAML);
        $file->remove($kitName);
        $file->save();

        $kit_menu = new Config(Loader::getInstance()->getDataFolder() . 'backup/kit_menu.json', Config::JSON);
        $data = $kit_menu->getAll();
        $key = array_search($kitName, $data);
        unset($data[$key]);
        $kit_menu->setAll($data);
        $kit_menu->save();
    }

    /**
     * @param string $kitName
     * @return Kit
     */
    public static function getKit(string $kitName): ?Kit
    {
        return self::isKit($kitName) ? self::$kits[$kitName] : null;
    }

    /**
     * @return Kit[]
     */
    public static function getKits(): array
    {
        return self::$kits;
    }
}