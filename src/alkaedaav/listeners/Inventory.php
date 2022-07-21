<?php

namespace alkaedaav\listeners;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\API\InvMenu\type\ChestInventory;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

use pocketmine\inventory\EnderChestInventory;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;

use pocketmine\event\player\{PlayerQuitEvent, PlayerInteractEvent};

class Inventory implements Listener {
	
	/**
	 * Inventory Constructor.
	 */
	public function __construct(){
		
	}
	
	/**
	 * @param InventoryCloseEvent $event
	 * @return void
	 */
	public function onInventoryCloseEvent(InventoryCloseEvent $event) : void {
		$player = $event->getPlayer();
		$inventory = $event->getInventory();
		if($inventory instanceof ChestInventory){
			$inventory->closeInventory($player);
		}
		if($inventory instanceof EnderChestInventory){
		    # This is to remove the chest when the player closes the inventory
            $position = $inventory->getHolder();

            $pk = new UpdateBlockPacket();
            $pk->x = $position->x;
            $pk->y = $position->y;
            $pk->z = $position->z;
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = Block::get(Block::AIR)->getRuntimeId();
            $player->dataPacket($pk);
        }
	}
	
	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
		$item = $event->getItem();
        if(in_array($item->getId(), Loader::getDefaultConfig("items_id"))){
        	$event->setCancelled(true);
        }
	}
	
	/**
	 * @param InventoryTransactionEvent $event
	 * @return void
     */
	public function onInventoryTransactionEvent(InventoryTransactionEvent $event) : void {
		$transaction = $event->getTransaction();
		foreach($transaction->getActions() as $action){
			if($action instanceof SlotChangeAction){
				$inventory = $action->getInventory();
				if($inventory instanceof ChestInventory){
					$event->setCancelled(true);
				}
			}
		}
	}
}

?>