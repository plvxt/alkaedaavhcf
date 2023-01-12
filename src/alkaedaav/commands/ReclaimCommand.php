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
	        		
				CrateManager::giveKey($sender, "starter", 30);
				CrateManager::giveKey($sender, "bolt", 16);
				CrateManager::giveKey($sender, "cosmica", 8);
				CrateManager::giveKey($sender, "partner", 10);
				CrateManager::giveKey($sender, "suerte", 3);
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
				CrateManager::giveKey($sender, "starter", 15);
				CrateManager::giveKey($sender, "bolt", 8);
				CrateManager::giveKey($sender, "cosmica", 4);
				CrateManager::giveKey($sender, "partner", 5);
				CrateManager::giveKey($sender, "suerte", 2);
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
				CrateManager::giveKey($sender, "starter", 8);
				CrateManager::giveKey($sender, "bolt", 4);
				CrateManager::giveKey($sender, "cosmica", 2);
				CrateManager::giveKey($sender, "partner", 3);
				CrateManager::giveKey($sender, "suerte", 1);
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
				CrateManager::giveKey($sender, "starter", 30);
				CrateManager::giveKey($sender, "bolt", 16);
				CrateManager::giveKey($sender, "cosmica", 8);
				CrateManager::giveKey($sender, "partner", 10);
				CrateManager::giveKey($sender, "suerte", 3);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
        		} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("moon.reclaim")||$sender->hasPermission("astronaut.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){         		try { 	        		$sender->resetReclaimTime();
								CrateManager::giveKey($sender, "Ordinary", 15);
				CrateManager::giveKey($sender, "Strange", 12);
				CrateManager::giveKey($sender, "Moon", 10);
				CrateManager::giveKey($sender, "HCF", 8);
				CrateManager::giveKey($sender, "Ability", 8);
				CrateManager::giveKey($sender, "Alexpolu", 8);
				#CrateManager::giveKey($sender, "Spidix", 8);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
        	} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
		if($sender->hasPermission("saturn.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){         		try { 	        		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "Ordinary", 12);
				CrateManager::giveKey($sender, "Strange", 10);
				CrateManager::giveKey($sender, "Moon", 8);
				CrateManager::giveKey($sender, "HCF", 6);
				CrateManager::giveKey($sender, "Ability", 6);
				CrateManager::giveKey($sender, "Alexpolu", 6);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
			} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
        if($sender->hasPermission("mercury.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){         		try { 	        		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "Ordinary", 8);
				CrateManager::giveKey($sender, "Strange", 6);
				CrateManager::giveKey($sender, "Moon", 4);
				CrateManager::giveKey($sender, "HCF", 3);
				CrateManager::giveKey($sender, "Ability", 5);
				CrateManager::giveKey($sender, "Alexpolu", 4);
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
        	} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("famous.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){         		try { 	       		$sender->resetReclaimTime();
				CrateManager::giveKey($sender, "Ordinary", 10);
				CrateManager::giveKey($sender, "Strange", 7);
				CrateManager::giveKey($sender, "Ability", 3);
				
				$sender->resetReclaimTime();
				Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}"], ["§", $sender->getName()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
        	} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());

				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("venus.reclaim")||$sender->hasPermission("miniyt.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){         		try { 	        		$sender->resetReclaimTime();
        	
				CrateManager::giveKey($sender, "Ordinary", 6);
				CrateManager::giveKey($sender, "Strange", 4);
				CrateManager::giveKey($sender, "Moon", 2);
				CrateManager::giveKey($sender, "HCF", 1);
				CrateManager::giveKey($sender, "Ability", 4);
				CrateManager::giveKey($sender, "Alexpolu", 2);
				
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