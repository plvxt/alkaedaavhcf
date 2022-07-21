<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class LoggerBait extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
     * Invisibility Constructor.
     */
    public function __construct(){
        parent::__construct(self::SPAWN_EGG, "Logger Bait", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Simulates a lack of disconnection"]);
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
    }

    /**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 64;
    }
}

?>