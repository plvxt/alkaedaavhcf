<?php

namespace alkaedaav\entities\spawnable;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use Advanced\Data\Data;
use Advanced\utils\Time;

use pocketmine\entity\Entity;
use pocketmine\nbt\NBT;

use pocketmine\utils\TextFormat as TE;
use pocketmine\item\Item;

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\nbt\tag\{DoubleTag, ListTag, StringTag};

class Villager extends \pocketmine\entity\passive\Villager {
	
	/** @var String */
	protected $player;
	
	/** @var Int */
	protected $time = 550;
	
	/** 
	 * @return void
	 */
	public function initEntity() : void {
        parent::initEntity();
        $this->setMaxHealth(60);
        $this->setHealth(60);
    }
	
	/**
	 * @param Int $tickDiff
	 * @return bool
	 */
	public function entityBaseTick(Int $tickDiff = 1) : bool {
		parent::entityBaseTick($tickDiff);
		if($this->closed){
			return false;
		}
		if(count(Loader::getInstance()->getServer()->getOnlinePlayers()) === 0){
			$this->close();
			return false;
		}
		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->player === null||$this->player === ""){
			$this->close();
			return false;
		}
		$player = Loader::getInstance()->getServer()->getPlayer($this->player);
		//TODO: check if the player reconnects to remove the entity
		if($player !== null){
			$this->close();
			return false;
		}
		if($this->time === 0){
			$this->close();
			return false;
		}else{
			$this->time--;
		}
		$this->setNameTag(TE::GRAY."[TARGET] ".TE::RED.$this->player.TE::GRAY);
		return true;
	}
	
	/**
	 * @param EntityDamageEvent $source
	 * @return void
	 */
	public function attack(EntityDamageEvent $source) : void {
		$itemDrop = [];
		if($this->getHealth() >= $source->getFinalDamage()){
			parent::attack($source);
			return;
		}
		if($source instanceof EntityDamageByEntityEvent){
			$damager = $source->getDamager();
			if($damager instanceof Player){
				if(Factions::inFaction($this->player) && Factions::inFaction($damager->getName()) && Factions::getFaction($this->player) === Factions::getFaction($damager->getName())){
					$source->setCancelled(true);
					return;
				}
				$damager->addKills();
				if(Factions::inFaction($this->player)) Factions::reduceStrength(Factions::getFaction($this->player));
			}
		}
		$dataFlag = Loader::getInstance()->getServer()->getOfflinePlayerData($this->player);
		$items = $dataFlag->getListTag("Inventory")->getAllValues();
		foreach($items as $item) {
			$item = Item::nbtDeserialize($item);
			$itemDrop[] = $item;
		}
		$spawn = Loader::getInstance()->getServer()->getDefaultLevel()->getSpawnLocation();
		$dataFlag->setTag(new ListTag("Inventory", [], NBT::TAG_Compound));
		$dataFlag->setTag(new ListTag("Pos", [
			new DoubleTag("", $spawn->x),
			new DoubleTag("", $spawn->y),
			new DoubleTag("", $spawn->z)
		], NBT::TAG_Double));
		$dataFlag->setTag(new StringTag("Level", Loader::getInstance()->getServer()->getDefaultLevel()->getFolderName()));
		Loader::getInstance()->getServer()->saveOfflinePlayerData($this->player, $dataFlag);
		foreach($itemDrop as $item) {
			$this->getLevel()->dropItem($this, $item);
		}
		$this->flagForDespawn();
	}
	
	/**
	 * @param Player $player
	 * @return void
	 */
	public function handleData(Player $player) : void {
		$this->player = $player->getName();
	}
}

?>