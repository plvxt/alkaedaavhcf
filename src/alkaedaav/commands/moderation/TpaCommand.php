<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class TpaCommand extends PluginCommand {
	
	/**
	 * TpaCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("tpa", Loader::getInstance());
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
		if(count($args) === 0){
			return;
		}
		if($args[0] == "all"){
			if(!$sender->isOp()){
				$sender->sendMessage(TE::RED."You have not permissions to use this command");
				return;
			}
			foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $players){
				$players->teleport($sender->getLocation());
			}
		}
	}
}

?>