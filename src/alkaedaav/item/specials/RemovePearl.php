<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class RemovePearl extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * RemovePearl Constructor.
	 */
	public function __construct(){
		parent::__construct(self::GHAST_TEAR, "Remove Pearl", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Set Cooldown Of Pearl : 0"]);
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