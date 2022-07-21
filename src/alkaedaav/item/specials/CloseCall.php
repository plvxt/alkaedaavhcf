<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class CloseCall extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * CloseCall Constructor.
	 */
	public function __construct(){
		parent::__construct(self::COOKIE, "Close Call", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Can get Strength 2 And Regeneration 5 for yourself"]);
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