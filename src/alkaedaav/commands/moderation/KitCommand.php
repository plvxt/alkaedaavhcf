<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\kit\{Kit, KitManager, KitBackup};
use alkaedaav\kit\utils\KitUtils;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as TE;

class KitCommand extends PluginCommand {
	
	/**
	 * KitCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("kit", Loader::getInstance());
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
        if(empty($args)){
			$sender->sendMessage(TE::RED."Use: /{$label} help (see list of commands)");
			return;
		}
		switch($args[0]){
			case 'editmenu':
			    KitUtils::openMenuEditKits($sender);
			    break;
			case "create":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])||empty($args[2])||empty($args[3])||empty($args[4])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kitName] [string: permission] [string: format] [string: item]");
					return;
				}
				if(KitManager::isKit($args[1])){
					$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("kit_alredy_exists")));
					return;
				}
				$item = explode(':', $args[4]);
				
                $kitData = [
                    "name" => $args[1],
                    "contents" => $sender->getInventory()->getContents(),
                    "armorContents" => $sender->getArmorInventory()->getContents(),
                    "permission" => $args[2],
                    "nameFormat" => $args[3],
                    "representativeItem" => Item::get((int) $item[0], (int) $item[1] ?? 0)
                ];
				KitManager::createKit($kitData);
				$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("kit_create_correctly")));
			break;
			case "delete":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kitName]");
					return;
				}
				if(!KitManager::isKit($args[1])){
					$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("kit_not_exists")));
					return;
				}
				KitManager::removeKit($args[1]);
				$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("kit_delete_correctly")));
			break;
			case "edit":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])||empty($args[2])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kitName] [string: args]");
					return;
				}
				if(!KitManager::isKit($args[1])){
					$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("kit_not_exists")));
					return;
				}
				switch($args[2]){
				    case "representativeItem":
				        if(empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kitName] {$args[2]} [string: item]");
							return;
						}
						$kit = KitManager::getKit($args[1]);
						$item = explode(':', $args[3]);
						$kit->setRepresentativeItem(Item::get((int) $item[0], (int) $item[1] ?? 0));
						$sender->sendMessage(TE::colorize('&aYou have successfully edited the representative item of the ' . $args[1] . ' kit'));
				    break;
					case "items":
						$kit = KitManager::getKit($args[1]);
						$kit->setItems($sender->getInventory()->getContents());
						$kit->setArmorItems($sender->getArmorInventory()->getContents());
						$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $kit->getName()], Loader::getConfiguration("messages")->get("kit_edit_items_correctly")));
					break;
					case "permission":
						if(empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kitName] {$args[2]} [string: new permission]");
							return;
						}
						$kit = KitManager::getKit($args[1]);
						$kit->setPermission($args[3]);
						$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $kit->getName()], Loader::getConfiguration("messages")->get("kit_edit_permission_correctly")));
					break;
					case "format":
						if(empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kitName] {$args[2]} [string: new format]");
							return;
						}
						$kit = KitManager::getKit($args[1]);
						$kit->setNameFormat($args[3]);
						$sender->sendMessage(str_replace(["&", "{kitName}"], ["§", $kit->getName()], Loader::getConfiguration("messages")->get("kit_edit_format_correctly")));
					break;
				}
			break;
			case "help":
			case "?":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				$sender->sendMessage(
					TE::YELLOW."/{$label} create [string: kitName] [string: permission] [string: format] [string: item] ".TE::GRAY."(To create a new kit)"."\n".
					TE::YELLOW."/{$label} delete [string: kitName] ".TE::GRAY."(To remove a kit from the list)"."\n".
					TE::YELLOW."/{$label} edit [string: kitName] [string: items:permission:format:representativeItem] ".TE::GRAY."(To edit the different data)"."\n".
					TE::colorize("&e/{$label} editmenu &7(To edit the organization kits)")
				);
			break;
            default:
                $sender->sendMessage(TE::RED."Unknown command. Try /help for a list of commands");
            break;
        }
	}
}

?>