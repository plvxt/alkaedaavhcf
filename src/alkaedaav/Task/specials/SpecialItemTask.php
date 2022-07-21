<?php

namespace alkaedaav\Task\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class SpecialItemTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * SpecialItemTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setSpecialItemTime(Loader::getDefaultConfig("Cooldowns")["SpecialItem"]);
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
        if($player->getSpecialItemTime() === 0){
            $player->setSpecialItem(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setSpecialItemTime($player->getSpecialItemTime() - 1);
        }
    }
}

?>