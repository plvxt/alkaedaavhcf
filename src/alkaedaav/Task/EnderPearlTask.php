<?php

namespace alkaedaav\Task;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class EnderPearlTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * EnderPearlTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setEnderPearlTime(Loader::getDefaultConfig("Cooldowns")["EnderPearl"]);
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
        if($player->isEnderPearl()){
            if($player->getEnderPearlTime() === 0){
                $player->setEnderPearl(false);
                Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            }else{
                $player->setEnderPearlTime($player->getEnderPearlTime() - 1);
            }
        }else{
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
    }
}

?>