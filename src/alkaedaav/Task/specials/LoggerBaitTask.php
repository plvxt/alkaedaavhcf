<?php


namespace alkaedaav\Task\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;

class LoggerBaitTask extends Task {

    /**
     * LoggerBaitTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setLoggerBaitTime(Loader::getDefaultConfig("Cooldowns")["LoggerBait"]);
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
            $player->setLoggerBaitTime($player->getLoggerBaitTime() - 1);
        }
    }
}