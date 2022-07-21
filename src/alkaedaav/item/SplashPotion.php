<?php

namespace alkaedaav\item;

use alkaedaav\Loader;

use pocketmine\item\Potion;
use pocketmine\level\sound\LaunchSound;

class SplashPotion extends \pocketmine\item\ProjectileItem {
	
	/**
	 * SplashPotion Constructor.
	 * @param Int $meta
	 */
	public function __construct($meta = 0){
		parent::__construct(\pocketmine\item\Item::SPLASH_POTION, $meta, "Splash Potion");
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
		return "SplashPotion";
	}
	
	/**
	 * @return float
	 */
	public function getThrowForce() : float {
        return 0.7;
	}
}

?>