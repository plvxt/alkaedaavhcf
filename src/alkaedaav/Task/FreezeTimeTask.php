<?php

namespace alkaedaav\Task;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class FreezeTimeTask extends Task {

    /** @var String */
    protected $factionName;

    /**
     * FreezeTimeTask Constructor.
     * @param String $factionName
     * @param Int $time
     */
    public function __construct(String $factionName, Int $time = 1800){
        $this->factionName = $factionName;
        Factions::setFreezeTime($factionName, $time);
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        $factionName = $this->factionName;
        if(!Factions::isFreezeTime($factionName)){
            Factions::setStrength($factionName, Factions::getMaxStrength($factionName));
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        if(Factions::getFreezeTime($factionName) === 0){
            Factions::setStrength($factionName, Factions::getMaxStrength($factionName));
            Factions::removeFreezeTime($factionName);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            Factions::setFreezeTime($factionName, Factions::getFreezeTime($factionName) - 1);
        }
    }
}

?>