<?php

namespace alkaedaav\commands;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

use pocketmine\inventory\CraftingGrid;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;

class CraftCommand extends PluginCommand {
	
	/**
	 * CraftCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("craft", Loader::getInstance());
		
        parent::setPermission("craft.command.use");
		parent::setDescription("Can open your Craft table");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!$sender->hasPermission("craft.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        $sender->setCraftingGrid(new CraftingGrid($sender, CraftingGrid::SIZE_BIG));
        if(!array_key_exists($windowId = Player::HARDCODED_CRAFTING_GRID_WINDOW_ID, $sender->openHardcodedWindows)){
            $pk = new ContainerOpenPacket();
            $pk->windowId = $windowId;
            $pk->type = WindowTypes::WORKBENCH;
            $pk->x = $sender->getFloorX();
            $pk->y = $sender->getFloorY() + 5;
            $pk->z = $sender->getFloorZ();
            $sender->sendDataPacket($pk);
            $sender->openHardcodedWindows[$windowId] = true;
        }
    }
}

?>