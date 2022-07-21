<?php

namespace alkaedaav\item;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Food;

class GoldenAppleEnchanted extends GoldenApple {
	
	/**
	 * GoldenAppleEnchanted Constructor.
	 * @param Int $meta
	 */
	public function __construct(Int $meta = 0){
		Food::__construct(\pocketmine\item\Item::ENCHANTED_GOLDEN_APPLE, $meta, "Enchanted Golden Apple");
	}
	
	/**
	 * @return Array
	 */
	public function getAdditionalEffects() : Array {
		return [
			new EffectInstance(Effect::getEffect(Effect::REGENERATION), 600, 4),
			new EffectInstance(Effect::getEffect(Effect::ABSORPTION), 2400, 3),
			new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 6000),
			new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 6000)
		];
	}
}

?>