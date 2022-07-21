<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class Sky extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
     * Firework constructor.
     */
    public function __construct(){
        parent::__construct(self::FEATHER, "Sky Ability", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Raises you blocks up for scape"]);
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
    }
}

?>