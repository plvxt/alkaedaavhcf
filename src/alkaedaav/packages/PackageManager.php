<?php

namespace alkaedaav\packages;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\item\specials\PartnerPackages;

use pocketmine\utils\{Config, TextFormat as TE};

class PackageManager {

    /** @var Package[] */
    protected static $packages = [];

    /**
     * @return bool
     */
    public static function isPackage() : bool {
        if(!empty(self::$packages)){
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
    public static function createPackage(array $args) : void {
        self::$packages[] = new Package($args["contents"]);
    }

    /**
     * @return void
     */
    public static function removePackage() : void {
        self::$packages = [];
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."packages.yml", Config::YAML);
        $file->remove("items");
        $file->save();
    }

    /**
     * @return Package
     */
    public static function getPackage() : ?Package {
        $value = null;
        if(self::isPackage()){
            foreach(array_values(self::$packages) as $package){
                $value = $package;
            }
        }
        return $value;
    }

    /**
     * @param Player $player
     * @param int $amount
     * @return void
     */
    public static function givePackage(Player $player, Int $amount = 1) : void {
        $package = new PartnerPackages();
        $package->setCount($amount);
        $player->getInventory()->addItem($package);
        $player->sendMessage(str_replace(["&", "{amount}"], ["§", $amount], Loader::getConfiguration("messages")->get("package_give_correctly")));
    }

    /**
	 * @return array[]
	 */
	public static function getRewards() : array {
		$items = self::getPackage()->getItems();
		
		$item = $items[array_rand($items)];
		return [$item];
	}
}

?>