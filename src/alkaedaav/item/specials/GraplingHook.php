<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class GraplingHook extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
     * Firework constructor.
     */
    public function __construct(){
        parent::__construct(self::FISHING_ROD, "Grapling Hook", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."You Avanced 5 Blocks"]);
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
    }
}

?>