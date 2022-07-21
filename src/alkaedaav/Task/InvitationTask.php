<?php

namespace alkaedaav\Task;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class InvitationTask extends Task {

    /** @var Player */
    protected $player;

    /** @var Int */
    protected $invitationTime = 0;

    /**
     * InvitationTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $this->invitationTime = 20;
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
        if(!$player->isInvited()){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        if($this->invitationTime === 0){
            $player->setInvite(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $this->invitationTime--;
        }
    }
}

?>