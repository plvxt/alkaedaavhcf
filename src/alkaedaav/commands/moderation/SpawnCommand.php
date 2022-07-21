<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class SpawnCommand extends PluginCommand {
	
	/**
	 * SpawnCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("spawn", Loader::getInstance());
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(Factions::isSpawnRegion($sender)){
			$sender->teleport(Loader::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
			return;
		}
		if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		if(empty($args)){
			$sender->teleport(Loader::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
			return;
		}
		$player = Loader::getInstance()->getServer()->getPlayer($args[0]);
		if($player instanceof Player){
			$player->teleport(Loader::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
		}
	}
}

?>