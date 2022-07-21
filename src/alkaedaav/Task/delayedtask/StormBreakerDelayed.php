<?php

namespace alkaedaav\Task\delayedtask;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\entity\Entity;
use pocketmine\item\ItemIds;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class StormBreakerDelayed extends Task {
	
	/** @var Player */
	protected $player;
	
	/**
	 * StormBreakerDelayed Constructor.
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		$player = $this->player;
		if(!$player->isOnline()){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
    	}
    	$helmet = $player->getArmorInventory()->getHelmet();	 
		if($helmet !== null){	 
			$itemRemove = $player->getInventory()->getItemInHand();							
			if($itemRemove->getId() === ItemIds::LEATHER_CHESTPLATE||$itemRemove->getId() === ItemIds::CHAINMAIL_CHESTPLATE||$itemRemove->getId() === ItemIds::IRON_CHESTPLATE||$itemRemove->getId() === ItemIds::DIAMOND_CHESTPLATE||$itemRemove->getId() === ItemIds::GOLDEN_CHESTPLATE||$itemRemove->getId() === ItemIds::LEATHER_LEGGINGS||$itemRemove->getId() === ItemIds::CHAINMAIL_LEGGINGS||$itemRemove->getId() === ItemIds::IRON_LEGGINGS||$itemRemove->getId() === ItemIds::DIAMOND_LEGGINGS||$itemRemove->getId() === ItemIds::GOLDEN_LEGGINGS){						
				return;							
			}	 
			if($itemRemove !== null){
				$player->getArmorInventory()->setHelmet($itemRemove);	 
				$player->getInventory()->setItemInHand($helmet);	 	
			}	 
		}
    	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
    }
}

?>