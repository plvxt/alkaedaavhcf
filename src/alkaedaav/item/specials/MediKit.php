<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class MediKit extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
     * Firework constructor.
     */
    public function __construct(){
        parent::__construct(self::REDSTONE, "Beserk", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Give Strength Regeneration Speed Night Vision for yourself"]);
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
    }
}

?>