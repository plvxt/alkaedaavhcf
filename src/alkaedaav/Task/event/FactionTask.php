<?php

namespace alkaedaav\Task\event;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class FactionTask extends Task {

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
    	$time = microtime(true);
    	try {
	    	foreach(Factions::getFactions() as $factionName){
	    		if(!Factions::isFreezeTime($factionName)){
	         	   Factions::setStrength($factionName, Factions::getMaxStrength($factionName));
	      	  }
	        }
            // Factions::backup();
	        Loader::getInstance()->getLogger()->info("Backup of factions was completed in: ".round((microtime(true) - $time), 3)." seconds");
		} catch(\Exception $exception){
			Loader::getInstance()->getLogger()->info($exception->getMessage());
		}
    }
}

?>