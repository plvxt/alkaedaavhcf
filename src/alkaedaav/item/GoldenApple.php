<?php

namespace alkaedaav\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

class GoldenApple extends \pocketmine\item\Food {
	
	/**
	 * GoldenApple Constructor.
	 * @param Int $meta
	 */
	public function __construct(Int $meta = 0){
		parent::__construct(\pocketmine\item\Item::GOLDEN_APPLE, $meta, "Golden Apple");
	}
	
	/**
	 * @return bool
	 */
	public function requiresHunger() : bool {
		return false;
	}
	
	/**
	 * @return float
	 */
	public function getSaturationRestore() : float {
		return 9.6;
	}
	
	/**
	 * @return Int
	 */
	public function getFoodRestore() : Int {
		return 6;
	}
	
	/**
	 * @return Array
	 */
	public function getAdditionalEffects() : Array {
		return [
			new EffectInstance(Effect::getEffect(Effect::REGENERATION), 100),
			new EffectInstance(Effect::getEffect(Effect::ABSORPTION), 2400),
			new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 1400),
		];
	}
}

?>