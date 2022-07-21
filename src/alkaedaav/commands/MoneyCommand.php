<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class MoneyCommand extends PluginCommand {
	
	/**
	 * MoneyCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("money", Loader::getInstance());
		
		parent::setDescription("Can see the total sum of your money");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
        $sender->sendMessage(str_replace(["&", "{money}"], ["§", $sender->getBalance()], Loader::getConfiguration("messages")->get("player_total_money")));
	}
}

?>