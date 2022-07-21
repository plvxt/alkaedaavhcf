<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;
use alkaedaav\player\Player;
use alkaedaav\utils\Enchantments;

use pocketmine\command\{PluginCommand, CommandSender};
use pocketmine\utils\{Config, TextFormat as TE};

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class EnchantCommand extends PluginCommand {
	
	/**
     * EnchantCommand Constructor.
     */
    public function __construct(){
        parent::__construct("enchant", Loader::getInstance());
        parent::setPermission("enchant.command.use");
    }
	
	/**
     * @param CommandSender $sender
     * @param String $label
     * @param Array $args
     * @return void
     */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->hasPermission("enchant.command.use")){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
        }
        if($args[0] === "list"){
        	$enchants = Enchantments::getEnchantments();
        	$sender->sendMessage(TE::AQUA."Enchantments".TE::WHITE.": ".TE::RESET.$enchants);
        	return;
        }
        if(empty($args[0])||empty($args[1])){
        	$sender->sendMessage(TE::RED."Use: /{$label} [string: playerName] [string: enchantmentName] [int: enchantmentLevel]");
        	return;
        }
        $player = Loader::getInstance()->getServer()->getPlayer($args[0]);
        if(!$player instanceof Player){
        	$sender->sendMessage(TE::RED."The player you are logged in is not connected!");
        	return;
        }
        $item = $player->getInventory()->getItemInHand();
        if($item->isNull()){
        	$sender->sendMessage(TE::RED."You must have an item in hand to use this");
        	return;
        }
        if(is_numeric($args[1])){
			$enchantment = Enchantment::getEnchantment((int) $args[1]);
		}else{
			$enchantment = Enchantment::getEnchantmentByName($args[1]);
		}
        if(!($enchantment instanceof Enchantment)){
        	$sender->sendMessage(TE::RED."The enchantment you are entering does not exist");
        	return;
        }
        $level = 1;
        if(!empty($args[2])){
        	$level = $args[2];
        }
        $item->addEnchantment(new EnchantmentInstance($enchantment, $level));
        $player->getInventory()->setItemInHand($item);
    }
}