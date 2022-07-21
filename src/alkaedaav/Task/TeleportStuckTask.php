<?php

namespace alkaedaav\Task;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class TeleportStuckTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * TeleportStuckTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setTeleportingStuckTime(Loader::getDefaultConfig("Cooldowns")["Stuck"]);
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
        if(!$player->isTeleportingStuck()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if($player->getTeleportingStuckTime() === 0){
            if($player->getY() < 40){
            	$player->setTeleportingStuck(false);
            	$player->teleport(new Vector3($player->getX() + 20, 75, $player->getZ() + 20, $player->getLevel()));
        	    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	}
        	if($player->getY() < 20){
        		$player->setTeleportingStuck(false);
        		$player->teleport(new Vector3($player->getX() + 20, 75, $player->getZ() + 20, $player->getLevel()));
        	    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	}
        	if($player->getY() < 10){
        		$player->setTeleportingStuck(false);
        		$player->teleport(new Vector3($player->getX() + 20, 75, $player->getZ() + 20, $player->getLevel()));
        	    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	}
        	if($player->getY() > 70){
        		$player->setTeleportingStuck(false);
        		$player->teleport(new Vector3($player->getX() + 20, 75, $player->getZ() + 20, $player->getLevel()));
        	    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	}else{
        		$player->setTeleportingStuck(false);
        		$player->teleport(new Vector3($player->getX() + 20, 75, $player->getZ() + 20, $player->getLevel()));
        	    Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	}
        }else{
            $player->setTeleportingStuckTime($player->getTeleportingStuckTime() - 1);
        }
    }
}

?>
