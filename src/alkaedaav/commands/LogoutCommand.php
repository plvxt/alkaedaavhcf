<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;
use alkaedaav\Task\LogoutTask;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class LogoutCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * LogoutCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("logout", Loader::getInstance());
		
		parent::setDescription("Can leave the server, without losing your things");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if($sender->isLogout()){
			$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_in_logout_time")));
			return;
		}
		$sender->setLogout(true);
		$sender->sendMessage(str_replace(["&", "{time}"], ["§", Loader::getDefaultConfig("Cooldowns")["Logout"]], Loader::getConfiguration("messages")->get("sender_logout")));
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new LogoutTask($sender), 20);
	}
}

?>