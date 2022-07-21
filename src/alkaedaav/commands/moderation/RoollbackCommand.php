<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\item\Items;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};

class RoollbackCommand extends PluginCommand {
	
	/**
	 * RoollbackCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("rb", Loader::getInstance());
		parent::setPermission("rb.command.use");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->hasPermission("rb.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(empty($args)){
			$sender->sendMessage(TE::RED."Use: /{$label} [string: playerName]");
			return;
		}
		$items = [];
		$armorItems = [];
		
		$config = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML);
		$file = $config->getAll();
		if(Loader::getInstance()->getServer()->getPlayer($args[0]) instanceof Player){
			if(!$config->exists(Loader::getInstance()->getServer()->getPlayer($args[0])->getName())){
				return;
			}
			$fileData = $file[Loader::getInstance()->getServer()->getPlayer($args[0])->getName()];
			if(isset($fileData["items"])){
                foreach($fileData["items"] as $slot => $item){
                    $items[$slot] = Items::itemDeserialize($item);
                }
            }
            if(isset($fileData["armorItems"])){
                foreach($fileData["armorItems"] as $slot => $item){
                    $armorItems[$slot] = Items::itemDeserialize($item);
                }
            }
            $sender->getInventory()->setContents($items);
            $sender->getArmorInventory()->setContents($armorItems);
            $sender->sendMessage(str_replace(["&", "{playerName}"], ["§", Loader::getInstance()->getServer()->getPlayer($args[0])->getName()], Loader::getConfiguration("messages")->get("player_roollback_correctly")));
            $config->remove(Loader::getInstance()->getServer()->getPlayer($args[0])->getName());
            $config->save();
            unset($fileData, $items, $armorItems);
		}else{
			if(!$config->exists($args[0])){
				return;
			}
			$fileData = $file[$args[0]];
			if(isset($fileData["items"])){
                foreach($fileData["items"] as $slot => $item){
                    $items[$slot] = Items::itemDeserialize($item);
                }
            }
            if(isset($fileData["armorItems"])){
                foreach($fileData["armorItems"] as $slot => $item){
                    $armorItems[$slot] = Items::itemDeserialize($item);
                }
            }
            $sender->getInventory()->setContents($items);
            $sender->getArmorInventory()->setContents($armorItems);
            $sender->sendMessage(str_replace(["&", "{playerName}"], ["§", $args[0]], Loader::getConfiguration("messages")->get("player_roollback_correctly")));
            $config->remove($args[0]);
            $config->save();
            unset($fileData, $items, $armorItems);
		}
	}
}

?>