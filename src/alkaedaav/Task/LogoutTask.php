<?php

namespace alkaedaav\Task;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class LogoutTask extends Task {

    /** @var Player */
    protected $player;

    /**
     * LogoutTask Constructor.
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $player->setLogoutTime(Loader::getDefaultConfig("Cooldowns")["Logout"]);
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
        if(!$player->isLogout()){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        	return;
        }
        if($player->getLogoutTime() === 0){
        	$player->setLogout(false);
        	$player->close("", TE::AQUA."[Logout]".TE::RESET." ".TE::GRAY."You have successfully logged out!");
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $player->setLogoutTime($player->getLogoutTime() - 1);
        }
    }
}

?>
