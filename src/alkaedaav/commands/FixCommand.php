<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\item\{Item, Armor, Tool};

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class FixCommand extends PluginCommand {
	
	/**
	 * FixCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("fix", Loader::getInstance());

		parent::setPermission("fixall.command.use");
		parent::setDescription("Can repair the items in your inventory or your hand");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(empty($args)){
            $sender->sendMessage(TE::RED."Argument #1 is not valid for command syntax");
            return;
		}
		switch($args[0]){
			case "all":
				if(!empty($args[1])){
					if($sender->hasPermission("fixallother.command.use")){
						$player = Loader::getInstance()->getServer()->getPlayer($args[1]);
						if(empty($player)){
							return;
						}
						foreach($player->getInventory()->getContents() as $slot => $item){
							if($item instanceof Tool||$item instanceof Armor){
								if($item->getDamage() > 0){
									$player->getInventory()->setItem($slot, $item->setDamage(0));
								}
							}
						}
						foreach($player->getArmorInventory()->getContents() as $slot => $item){
							if($item instanceof Tool||$item instanceof Armor){
								if($item->getDamage() > 0){
									$player->getArmorInventory()->setItem($slot, $item->setDamage(0));
								}
							}
						}
						$sender->sendMessage(str_replace(["&", "{playerName}"], ["§", $player->getName()], Loader::getConfiguration("messages")->get("player_fixall_other_player_correctly")));
					}
				}else{
					if(!$sender->hasPermission("fixall.command.use")){
						$sender->sendMessage(TE::RED."You have not permissions to use this command");
						return;
					}
					foreach($sender->getInventory()->getContents() as $slot => $item){
						if($item instanceof Tool||$item instanceof Armor){
							if($item->getDamage() > 0){
								$sender->getInventory()->setItem($slot, $item->setDamage(0));
							}
						}
					}
					foreach($sender->getArmorInventory()->getContents() as $slot => $item){
						if($item instanceof Tool||$item instanceof Armor){
							if($item->getDamage() > 0){
								$sender->getArmorInventory()->setItem($slot, $item->setDamage(0));
							}
						}
					}
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_fixall_correctly")));
				}
			break;
			case "hand":
				if(!$sender->hasPermission("fixhand.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				$item = $sender->getInventory()->getItemInHand();
				if($item instanceof Tool||$item instanceof Armor){
					if($item->getDamage() > 0){
						$sender->getInventory()->setItemInHand($item->setDamage(0));
					}
				}
				$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_fixhand_correctly")));
			break;
		}
	}
}

?>