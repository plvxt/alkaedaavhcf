<?php

namespace alkaedaav\Task;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class GoldenGappleTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * GoldenGappleTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setGoldenAppleTime(Loader::getDefaultConfig("Cooldowns")["Apple"]);
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
        if($player->getGoldenAppleTime() === 0){
            $player->setGoldenApple(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setGoldenAppleTime($player->getGoldenAppleTime() - 1);
        }
    }
}

?>