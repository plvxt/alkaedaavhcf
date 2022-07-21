<?php

namespace alkaedaav\Task\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class RogueTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * RogueTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setRogueTime(Loader::getDefaultConfig("Cooldowns")["RogueItem"]);
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
        if($player->getRogueTime() === 0){
            $player->setRogueItem(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setRogueTime($player->getRogueTime() - 1);
        }
    }
}

?>