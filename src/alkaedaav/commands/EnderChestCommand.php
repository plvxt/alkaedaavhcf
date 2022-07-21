<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\API\InvMenu\type\EnderChestInventory;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class EnderChestCommand extends PluginCommand {
	
	/**
	 * EnderChestCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("enderchest", Loader::getInstance());
		
		parent::setAliases(["ec"]);
		parent::setDescription("Can open your EnderChest");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		$inventory = new EnderChestInventory();
		$inventory->openInventory($sender);
	}
}

?>