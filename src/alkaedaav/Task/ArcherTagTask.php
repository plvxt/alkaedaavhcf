<?php

namespace alkaedaav\Task;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class ArcherTagTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * ArcherTagTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setArcherTagTime(Loader::getDefaultConfig("Cooldowns")["ArcherTag"]);
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
        if(!$player->isArcherTag()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if($player->getArcherTagTime() === 0){
            $player->setArcherTag(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setArcherTagTime($player->getArcherTagTime() - 1);
        }
    }
}

?>