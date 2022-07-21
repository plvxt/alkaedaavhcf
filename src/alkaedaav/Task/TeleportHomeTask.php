<?php

namespace alkaedaav\Task;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class TeleportHomeTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * TeleportHomeTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setTeleportingHomeTime(Loader::getDefaultConfig("Cooldowns")["Home"]);
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
        if(!$player->isTeleportingHome()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if(!Factions::inFaction($player->getName())){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if($player->getTeleportingHomeTime() === 0){
            $player->teleport(Factions::getFactionHomeLocation(Factions::getFaction($player->getName())));
            $player->setTeleportingHome(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setTeleportingHomeTime($player->getTeleportingHomeTime() - 1);
        }
    }
}

?>