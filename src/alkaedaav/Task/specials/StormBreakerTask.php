<?php

namespace alkaedaav\Task\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class StormBreakerTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * StormBreakerTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setStormBreakerTime(Loader::getDefaultConfig("Cooldowns")["StormBreaker"]);
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
        if($player->getStormBreakerTime() === 0){
            $player->setStormBreaker(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setStormBreakerTime($player->getStormBreakerTime() - 1);
        }
    }
}

?>