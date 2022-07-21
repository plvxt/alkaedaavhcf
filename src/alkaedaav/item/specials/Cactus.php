<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class Cactus extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * Cactus Constructor.
	 */
	public function __construct(){
		parent::__construct(self::CACTUS, "Cactus", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Can get Strength 2 And Resistance 3 for yourself"]);
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