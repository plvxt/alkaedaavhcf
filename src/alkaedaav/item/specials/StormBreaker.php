<?php

namespace alkaedaav\item\specials;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class StormBreaker extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	const USES_LEFT = "Uses left";
	
	/**
	 * StormBreaker Constructor.
	 * @param Int $usesLeft
	 */
	public function __construct(Int $usesLeft = 5){
		parent::__construct(self::GOLD_AXE, TE::YELLOW.TE::BOLD."Stormbreaker", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Remove the enemy's helmet after 3 seconds"."\n\n".TE::YELLOW."Uses Left: ".TE::GOLD.$usesLeft]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->getNamedTagEntry(self::CUSTOM_ITEM)->setInt(self::USES_LEFT, $usesLeft);
	}
	
	/**
	 * @param Player $player
	 * @return void
	 */
	public function reduceUses(Player $player) : void {
		$nbt = $this->getNamedTagEntry(self::CUSTOM_ITEM)->getInt(self::USES_LEFT);
		if($nbt > 0){
			$nbt--;
			if($nbt === 0){
				$player->getInventory()->setItemInHand(self::get(self::AIR));
			}else{
				$this->getNamedTagEntry(self::CUSTOM_ITEM)->setInt(self::USES_LEFT, $nbt);
				$this->setLore([TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::YELLOW."Uses Left: ".TE::GOLD.$nbt]);
				$player->getInventory()->setItemInHand($this);
			}
		}
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 1;
    }
}

?>