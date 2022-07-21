<?php

namespace alkaedaav\Task\event;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\listeners\event\Airdrop;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class AirdropTask extends Task {
	
	/**
	 * AirdropTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		Airdrop::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!Airdrop::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(Airdrop::getTime() === 0){
			Airdrop::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			Airdrop::setTime(Airdrop::getTime() - 1);
		}
	}
}

?>