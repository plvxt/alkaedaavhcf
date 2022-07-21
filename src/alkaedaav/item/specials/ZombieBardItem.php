<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class ZombieBardItem extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * RemovePearl Constructor.
	 */
	public function __construct(){
		parent::__construct(self::SPAWN_EGG, "Bard Portable", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Spawn the Bard Portable 20 health"]);
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