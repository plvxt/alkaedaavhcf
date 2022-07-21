<?php

namespace alkaedaav\item;

use alkaedaav\Loader;

use pocketmine\level\sound\LaunchSound;

class FishingRod extends \pocketmine\item\ProjectileItem {
	
	/**
	 * FishingRod Constructor.
	 * @param Int $meta
	 */
	public function __construct($meta = 0){
		parent::__construct(\pocketmine\item\Item::FISHING_ROD, $meta, "Fishing Rod");
	}
	
	/**
	 * @param Player $player
	 * @param Vector3 $directionVector
	 */
	public function onClickAir(\pocketmine\Player $player, \pocketmine\math\Vector3 $directionVector) : bool {
		return true;
	}
	
	/**
	 * @return Int
	 */
	public function getMaxStackSize() : Int {
		return 1;
	}
	
	/**
	 * @return String
	 */
	public function getProjectileEntityType() : String {
		return "Fishing Rod";
	}
	
	/**
	 * @return float
	 */
	public function getThrowForce() : float {
        return 2.1;
	}
}

?>