<?php

namespace alkaedaav\Task\event;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\{Player, PlayerBase};

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class InvincibilityTask extends Task {
	
	/** @var Player */
	protected $player;
	
	/**
	 * InvincibilityTask Constructor.
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
		$player->setInvincibilityTime(PlayerBase::getData($player->getName())->get("pvp_time"));
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
		if(!$player->isInvincibility()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
		if(!PlayerBase::isData($player->getName(), "pvp_time")){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;	
		}
		if($player->getInvincibilityTime() === 0){
			PlayerBase::removeData($player->getName(), "pvp_time");
			$player->setInvincibility(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			if(Factions::isSpawnRegion($player)) return;
			$player->setInvincibilityTime($player->getInvincibilityTime() - 1);
		}
	}
}

?>