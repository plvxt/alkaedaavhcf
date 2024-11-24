<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\{Player, PlayerBase};

use alkaedaav\crate\CrateManager;
use alkaedaav\utils\Time;

use pocketmine\item\{Item, ItemIds};
use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class ReclaimCommand extends PluginCommand {
	
	/**
	 * ReclaimCommand Constructor.
	 */
	 
	
	public function __construct(){
		parent::__construct("reclaim", Loader::getInstance());
		$this->setDescription("Reclaim your daily keys");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if($sender->isOp()){
        	if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
	        		
				CrateManager::giveKey($sender, "common", 64);
				CrateManager::giveKey($sender, "sandia", 64);
				CrateManager::giveKey($sender, "sandiap", 64);
				CrateManager::giveKey($sender, "partner", 64);
				CrateManager::giveKey($sender, "mineral", 64);
				CrateManager::giveKey($sender, "Koth", 10);
				#$sender->sendMessage("recla ddadl");
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
        		} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
		if($sender->hasPermission("vip.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "common", 64);
				CrateManager::giveKey($sender, "sandia", 60);
				CrateManager::giveKey($sender, "sandiap", 50);
				CrateManager::giveKey($sender, "partner", 40);
				CrateManager::giveKey($sender, "mineral", 64);
				CrateManager::giveKey($sender, "Koth", 3);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
		      	} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
		if($sender->hasPermission("user.reclaim")){
if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "common", 25);
				CrateManager::giveKey($sender, "sandia", 20);
				CrateManager::giveKey($sender, "sandiap", 18);
				CrateManager::giveKey($sender, "partner", 15);
				CrateManager::giveKey($sender, "mineral", 30);
				CrateManager::giveKey($sender, "Koth", 1);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("user_reclaim")));
        		} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
		if($sender->hasPermission("extra.reclaim")){
if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "common", 40);
				CrateManager::giveKey($sender, "sandia", 30);
				CrateManager::giveKey($sender, "sandiap", 25);
				CrateManager::giveKey($sender, "partner", 20);
				CrateManager::giveKey($sender, "mineral", 64);
				CrateManager::giveKey($sender, "Koth", 2);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("user_reclaim")));
        		} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
		if($sender->hasPermission("ultra.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "common", 64);
				CrateManager::giveKey($sender, "sandia", 64);
				CrateManager::giveKey($sender, "sandiap", 64);
				CrateManager::giveKey($sender, "partner", 64);
				CrateManager::giveKey($sender, "mineral", 64);
				CrateManager::giveKey($sender, "Koth", 10);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
        		} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
    }
}

?>