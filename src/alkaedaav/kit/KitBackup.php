<?php

namespace alkaedaav\kit;

use Exception;
use alkaedaav\Loader;
use alkaedaav\item\Items;
use pocketmine\utils\Config;

class KitBackup
{

    public static function initAll(): void
    {
        $file = new Config(Loader::getInstance()->getDataFolder() . "backup/kits.yml", Config::YAML);
        //$menuKit = new Config(Loader::getInstance()->getDataFolder() . 'backup/kit_menu.json', Config::JSON, []);
        foreach ($file->getAll() as $name => $values) {
            self::init($name);
        }
    }

    /**
     * @return void
     */
    public static function saveAll(): void
    {
        foreach (KitManager::getKits() as $name => $values) {
            self::save($name);
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function init(string $name): void
    {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder() . "backup/kits.yml", Config::YAML);
            $kitData = $file->getAll()[$name];

            if (isset($kitData["contents"])) {
                foreach ($kitData["contents"] as $slot => $item) {
                    $kitData["contents"][$slot] = Items::itemDeserialize($item);
                }
            }
            if (isset($kitData["armorContents"])) {
                foreach ($kitData["armorContents"] as $slot => $item) {
                    $kitData["armorContents"][$slot] = Items::itemDeserialize($item);
                }
            }

            $kitData['representativeItem'] = $kitData['representativeItem'] == null ? null : Items::itemDeserialize($kitData['representativeItem']);
            KitManager::createKit($kitData, false);
        } catch (Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load kit: " . $name);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }

    /**
     * @param string $name
     */
    public static function save(string $name): void
    {
        try {
            $kitData = [];

            $kit = KitManager::getKit($name);
            $file = new Config(Loader::getInstance()->getDataFolder() . "backup" . DIRECTORY_SEPARATOR . "kits.yml", Config::YAML);

            $kitData["name"] = $kit->getName();
            $kitData["permission"] = $kit->getPermission();
            $kitData["nameFormat"] = $kit->getNameFormat();

            foreach ($kit->getItems() as $slot => $item) {
                $kitData["contents"][$slot] = Items::itemSerialize($item);
            }
            foreach ($kit->getArmorItems() as $slot => $item) {
                $kitData["armorContents"][$slot] = Items::itemSerialize($item);
            }

            $kitData['representativeItem'] = $kit->getRepresentativeItem() == null ? null : Items::itemSerialize($kit->getRepresentativeItem());
            $file->set($kit->getName(), $kitData);
            $file->save();
        } catch (Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't save kit: " . $name);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}