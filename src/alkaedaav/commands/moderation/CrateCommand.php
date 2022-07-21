<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\crate\{Crate, CrateManager};

use pocketmine\item\{Item, ItemIds};

use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\utils\TextFormat as TE;
use pocketmine\level\particle\FloatingTextParticle;

use pocketmine\command\{PluginCommand, CommandSender};

class CrateCommand extends PluginCommand {
	
	/**
	 * CrateCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("crates", Loader::getInstance());
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
            case "create":
                if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])||empty($args[2])||empty($args[3])||empty($args[4])||empty($args[5])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: crateName] [string: crateBlock] [string: crateKey] [string: keyName] [string: format]");
					return;
				}
				if(CrateManager::isCrate($args[1])){
					$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("crate_alredy_exists")));
					return;
				}
				$crateData = [
				    "name" => $args[1],
                    "contents" => $sender->getInventory()->getContents(),
                    "block_id" => $args[2],
                    "key_id" => $args[3],
                    "keyName" => $args[4],
                    "nameFormat" => $args[5],
                ];
				CrateManager::createCrate($crateData);
				$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("crate_create_correctly")));
            break;
            case "delete":
                if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: crateName]");
					return;
				}
				if(!CrateManager::isCrate($args[1])){
					$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("crate_not_exists")));
					return;
				}
				CrateManager::removeCrate($args[1]);
				$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("crate_delete_correctly")));
            break;
			case "edit":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])||empty($args[2])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: crateName] [string: args]");
					return;
				}
				if(!CrateManager::isCrate($args[1])){
					$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("crate_not_exists")));
					return;
				}
				switch($args[2]){
					case "items":
						$crate = CrateManager::getCrate($args[1]);
						$crate->setItems($sender->getInventory()->getContents());
						$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $crate->getName()], Loader::getConfiguration("messages")->get("crate_edit_items_correctly")));
					break;
					case "block":
						if(empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: crateName] {$args[2]} [string: new blockId]");
							return;
						}
						$crate = CrateManager::getCrate($args[1]);
						$crate->setBlock($args[3]);
						$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $crate->getName()], Loader::getConfiguration("messages")->get("crate_edit_block_correctly")));
					break;
					case "format":
						if(empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: crateName] {$args[2]} [string: new format]");
							return;
						}
						$crate = CrateManager::getCrate($args[1]);
						$crate->setNameFormat($args[3]);
						$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $crate->getName()], Loader::getConfiguration("messages")->get("crate_edit_format_correctly")));
					break;
				    case "keyformat":
						if(empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: crateName] {$args[2]} [string: new format]");
							return;
						}
						$crate = CrateManager::getCrate($args[1]);
						$crate->setKeyName(str_replace(["&"], ["§"], $args[3]));
						$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $crate->getName()], Loader::getConfiguration("messages")->get("crate_edit_key_format_correctly")));
					break;
				}
			break;
			case "give":
            	if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty(CrateManager::getCrates())){
					$sender->sendMessage(TE::RED."Crates is empty, not have keys");
					return;
				}
				if(empty($args[1])){
					foreach(CrateManager::getCrates() as $crate){
						CrateManager::giveKey($sender, $crate->getName(), 10, $sender->getName());
					}
					return;
				}
				switch($args[1]){
					case "all":
						if(empty($args[2])||empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} {$args[1]} [string: crateName] [Int: amount]");
							return;
						}
						if(!CrateManager::isCrate($args[2])){
							$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[2]], Loader::getConfiguration("messages")->get("crate_not_exists")));
							return;
						}
						foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
							CrateManager::giveKey($player, $args[2], $args[3], $sender->getName());
						}
					break;
					case "items":
						if(empty($args[2])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} {$args[1]} [string: crateName]");
							return;
						}
						if(!CrateManager::isCrate($args[2])){
							$sender->sendMessage(str_replace(["&", "{crateName}"], ["§", $args[2]], Loader::getConfiguration("messages")->get("crate_not_exists")));
							return;
						}
						$crate = CrateManager::getCrate($args[2]);
						$sender->getInventory()->setContents($crate->getItems());
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
					TE::YELLOW."/{$label} create [string: crateName] [string: crateBlock] [string: crateKey] [string: keyName] [string: format] ".TE::GRAY."(To create a new crate)"."\n".
					TE::YELLOW."/{$label} delete [string: crateName] ".TE::GRAY."(To remove a crate from the list)"."\n".
					TE::YELLOW."/{$label} edit [string: items:format:block] ".TE::GRAY."(To edit the different data)"."\n".
					TE::YELLOW."/{$label} give [string: key:items] ".TE::GRAY."(To get keys or get the items from the crate)"
				);
			break;
            default:
                $sender->sendMessage(TE::RED."Unknown command. Try /help for a list of commands");
            break;
        }
	}
}

?>