<?php

namespace alkaedaav\Task\event;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\listeners\event\SOTW;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class SOTWTask extends Task {
	
	/**
	 * SOTWTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		SOTW::setTime($time);
	}
	
	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!SOTW::isEnable()){
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $online){
			$online->hidePlayer($online);
		}
		if(SOTW::getTime() === 0){
			foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $online){
				$online->showPlayer($online);
			}
			SOTW::setEnable(false);
			Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			SOTW::setTime(SOTW::getTime() - 1);
		}
	}
}

?>