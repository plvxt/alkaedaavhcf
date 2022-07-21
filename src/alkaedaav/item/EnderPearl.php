<?php

namespace alkaedaav\item;

use alkaedaav\Loader;

use pocketmine\level\sound\LaunchSound;

class EnderPearl extends \pocketmine\item\ProjectileItem {
	
	/**
	 * EnderPearl Constructor.
	 * @param Int $meta
	 */
	public function __construct($meta = 0){
		parent::__construct(\pocketmine\item\Item::ENDER_PEARL, $meta, "Ender Pearl");
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
		return 16;
	}
	
	/**
	 * @return String
	 */
	public function getProjectileEntityType() : String {
		return "EnderPearl";
	}
	
	/**
	 * @return float
	 */
	public function getThrowForce() : float {
		return 2.1;
	}

	/**
	 * @return Int
	 */
	public function getUniqueId() : Int {
		return 89898989100;
	}
}

?>