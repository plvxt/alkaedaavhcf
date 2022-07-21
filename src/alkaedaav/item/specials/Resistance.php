<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class Resistance extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * Resistance Constructor.
	 */
	public function __construct(){
		parent::__construct(self::IRON_INGOT, "Resistance 3", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Can get Resistance 3 for yourself"]);
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