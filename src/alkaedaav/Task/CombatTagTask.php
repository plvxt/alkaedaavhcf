<?php

namespace alkaedaav\Task;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class CombatTagTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * CombatTagTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setCombatTagTime(Loader::getDefaultConfig("Cooldowns")["CombatTag"]);
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
        if(!$player->isCombatTag()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if($player->getCombatTagTime() === 0){
            $player->setCombatTag(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setCombatTagTime($player->getCombatTagTime() - 1);
        }
    }
}

?>