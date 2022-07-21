<?php

namespace alkaedaav\commands\events;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\utils\{Time, Translator};

use alkaedaav\koth\KothManager;

use alkaedaav\Task\event\KothTask;

use alkaedaav\API\System;
use alkaedaav\utils\Tower;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class KothCommand extends PluginCommand {
	
	/** @var Int */
	protected $taskId;
	
	/**
	 * KothCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("koth", Loader::getInstance());

		parent::setPermission("koth.command.use");
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
			case "create":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->addTool();
                    $sender->setInteract(true);
				}else{
					if(KothManager::isKoth($args[1])){
						$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("koth_alredy_exists")));
						return;
					}
					if(!System::isPosition($sender, 1) && !System::isPosition($sender, 2)){
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_zone_location")));
                        return;
					}
					$kothData = [
						"name" => $args[1],
						"levelName" => $sender->getLevel()->getFolderName(),
						"position1" => System::getPosition($sender, 1),
						"position2" => System::getPosition($sender, 2),
					];
					KothManager::createKoth($kothData);

					Tower::delete($sender, 1);
                    Tower::delete($sender, 2);
					System::deletePosition($sender, 1, true);
					System::deletePosition($sender, 2, true);
					$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("koth_create_correctly")));
				}
			break;
			case "delete":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kothName]");
					return;
				}
				if(!KothManager::isKoth($args[1])){
					$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("koth_not_exists")));
					return;
				}
				KothManager::removeKoth($args[1]);
				$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("koth_delete_correctly")));
			break;
			case "start":
				if(!$sender->hasPermission("koth.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kothName]");
					return;
				}
				if(!KothManager::isKoth($args[1])){
					$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("koth_not_exists")));
					return;
				}
				if(($kothName = KothManager::kothIsEnabled())){
        			$koth = KothManager::getKoth($kothName);
					$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $koth->getName()], Loader::getConfiguration("messages")->get("koth_alredy_enable")));
					return;
				}
				if(!empty($args[2])){
					if(!in_array(Translator::intToString($args[2]), Translator::VALID_FORMATS)){
						$sender->sendMessage(TE::RED."The time format you enter is invalid!");
						return;
					}
				}
				if($sender->isOp()){
					$this->taskId = Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new KothTask($args[1], !empty($args[2]) ? Translator::getStringFormatToInt(Translator::stringToInt($args[2]), $args[2]) : null), 20)->gettaskId();
				}else{
					if($sender->getKothHostTimeRemaining() > time()){
						$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getKothHostTimeRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
						return;
					}
					$sender->resetKothHostTime();
					$this->taskId = Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new KothTask($args[1], null), 20)->gettaskId();
				}
			break;
			case "stop":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: kothName]");
					return;
				}
				if(!KothManager::isKoth($args[1])){
					$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("koth_not_exists")));
					return;
				}
				$koth = KothManager::getKoth($args[1]);
				if(!$koth->isEnable()){
					$sender->sendMessage(str_replace(["&", "{kothName}"], ["§", $koth->getName()], Loader::getConfiguration("messages")->get("koth_is_not_activated")));
					return;
				}
				$koth->setEnable(false);
				Loader::getInstance()->getScheduler()->cancelTask($this->taskId);
			break;
			case "list":
				if(empty(KothManager::getKoths())){
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("koth_not_have_arenas")));
					return;
				}
				$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("koth_list_of_arenas")));
				foreach(array_values(KothManager::getKoths()) as $koth){
					$sender->sendMessage(str_replace(["&", "{kothName}", "{position}", "{worldName}", "{status}"], ["§", $koth->getName(), Translator::vector3ToString($koth->getPosition1()), $koth->getLevel(), $koth->isEnable() ? TE::GREEN."RUNNING" : TE::RED."IDLE"], Loader::getConfiguration("messages")->get("koth_list")));
				}
			break;
			case "help":
			case "?":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				$sender->sendMessage(
					TE::YELLOW."/{$label} create [string: kothName] ".TE::GRAY."(To create a new koth and register its positions)"."\n".
					TE::YELLOW."/{$label} delete [string: kothName] ".TE::GRAY."(To remove a koth from the list)"."\n".
					TE::YELLOW."/{$label} start [string: kothName] ".TE::GRAY."(To start a KOTH event)"."\n".
					TE::YELLOW."/{$label} list  ".TE::GRAY."(To see the list of registered koths)"
				);
			break;
			default:
                $sender->sendMessage(TE::RED."Unknown command. Try /help for a list of commands");
            break;
		}
	}
}

?>