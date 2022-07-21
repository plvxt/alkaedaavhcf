<?php

namespace alkaedaav\Task\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class AntiTrapperTask extends Task {

    /** @var Player */
    protected $player, $damager;

    /**
     * AntiTrapperTask Constructor.
     * @param Player $player
     * @param Player $damager
     */
    public function __construct(Player $player, Player $damager){
        $this->player = $player;
        $this->damager = $damager;
        $player->setAntiTrapperTime(Loader::getDefaultConfig("Cooldowns")["AntiTrapper"]);
        $damager->setAntiTrapperTime(Loader::getDefaultConfig("Cooldowns")["AntiTrapperTarget"]);
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
        if($player->getAntiTrapperTime() === 0){
            $player->setAntiTrapper(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setAntiTrapperTime($player->getAntiTrapperTime() - 1);
        }
        $damager = $this->damager;
        if($damager->getAntiTrapperTime() === 0){
            $damager->setAntiTrapperTarget(false);
        }else{
            $damager->setAntiTrapperTime($damager->getAntiTrapperTime() - 1);
        }
    }
}

?>