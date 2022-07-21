<?php


namespace alkaedaav\Task\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class PotionCounterTask extends Task {

    /**
     * PotionCounterTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setPotionCounterTime(Loader::getDefaultConfig("Cooldowns")["PotionCounter"]);
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
        if($player->getPotionCounterTime() === 0){
            $player->setPotionCounter(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setPotionCounterTime($player->getPotionCounterTime() - 1);
        }
    }
}