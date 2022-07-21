<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class PayCommand extends PluginCommand {
	
	/**
	 * PayCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("pay", Loader::getInstance());
		
		parent::setDescription("Pay money to other players");
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
		if(!is_numeric($args[1])){
			$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
			return;
		}
		if(is_float($args[1])){
			$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_float_number")));
			return;
		}
		$player = Loader::getInstance()->getServer()->getPlayer($args[0]);
		if(empty($player)){
			$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_is_online")));
			return;
		}
		if($player->getName() === $sender->getName()){
			return;
		}
		if($sender->getBalance() < $args[1]||$args[1] < 0){
			$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_money_not_enough")));
			return;
		}
		$sender->reduceBalance($args[1]);
		$player->addBalance($args[1]);
		$sender->sendMessage(str_replace(["&", "{playerName}", "{money}"], ["§", $player->getName(), $args[1]], Loader::getConfiguration("messages")->get("player_pay_money_correctly")));
	}
}

?>