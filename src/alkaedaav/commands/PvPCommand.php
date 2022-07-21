<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\{Player, PlayerBase};

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class PvPCommand extends PluginCommand {
	
	/**
	 * PvPCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("pvp", Loader::getInstance());
		
		parent::setDescription("Disable your invincibility timer");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(empty($args)){
			$sender->sendMessage(
				TE::YELLOW."/{$label} enable ".TE::GRAY."(To deactivate your invincibility timer)"
			);
			return;
		}
		switch($args[0]){
			case "enable":
				if(!$sender->isInvincibility()){
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_invincibility_is_enable")));
					return;
				}
				PlayerBase::removeData($sender->getName(), "pvp_time");
				$sender->setInvincibility(false);
				$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_invisibility_enable_correnctly")));
			break;
			case "lives":
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} [string: check:give]");
					return;
				}
				switch($args[1]){
					case "check":
						if(!empty($args[2])){
							$player = Loader::getInstance()->getServer()->getPlayer($args[2]);
							if($player instanceof Player) $sender->sendMessage(str_replace(["&", "{playerName}", "{currentLives}"], ["§", $player->getName(), $player->getLives()], Loader::getConfiguration("messages")->get("player_check_other_player_lives")));
							return;
						}
						$sender->sendMessage(str_replace(["&", "{currentLives}"], ["§", $sender->getLives()], Loader::getConfiguration("messages")->get("player_check_lives")));
					break;
					case "give":
						if(empty($args[2])||empty($args[3])){
							$sender->sendMessage(TE::RED."Use: /{$label} {$args[1]} [string: playerName] [int: lives]");
							return;
						}
						if(!is_numeric($args[3])){
							$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
							return;
						}
						if(is_float($args[3])){
							$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_float_number")));
							return;
						}
						if(($player = Loader::getInstance()->getServer()->getPlayer($args[2])) instanceof Player){
							if($sender->getLives() < $args[3]){
								$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_doesnt_have_enough_lives")));
								return;
							}
							$player->addLives(intval($args[3]));
							$sender->reduceLives(intval($args[3]));
							$sender->sendMessage(str_replace(["&", "{playerName}", "{lives}"], ["§", $player->getName(), $args[3]], Loader::getConfiguration("messages")->get("player_give_correctly_lives")));
						}else{
							if($sender->getLives() < $args[3]){
								$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_doesnt_have_enough_lives")));
								return;
							}
							PlayerBase::setData($args[2], "lives", intval($args[3]));
							$sender->reduceLives(intval($args[3]));
							$sender->sendMessage(str_replace(["&", "{playerName}", "{lives}"], ["§", $args[2], $args[3]], Loader::getConfiguration("messages")->get("player_give_correctly_lives")));
						}
					break;
				}
			break;
			case "setlives":
				if(empty($args[1])||empty($args[2])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: playerName] [int: lives]");
					return;
				}
				if(!is_numeric($args[2])){
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
					return;
				}
				if(is_float($args[2])){
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_float_number")));
					return;
				}
				if(($player = Loader::getInstance()->getServer()->getPlayer($args[1])) instanceof Player){
					$player->setLives(intval($args[2]));
					$sender->sendMessage(str_replace(["&", "{playerName}", "{lives}"], ["§", $player->getName(), $args[2]], Loader::getConfiguration("messages")->get("player_give_correctly_lives")));
				}else{
					PlayerBase::setData($args[1], "lives", intval($args[2]));
					$sender->sendMessage(str_replace(["&", "{playerName}", "{lives}"], ["§", $args[1], $args[2]], Loader::getConfiguration("messages")->get("player_give_correctly_lives")));
				}
			break;
		}
	}
}

?>