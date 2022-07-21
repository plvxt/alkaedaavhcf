<?php

namespace alkaedaav\entities\spawnable;

use alkaedaav\Loader;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

use pocketmine\entity\{Effect, EffectInstance};

class Turtle extends Monster {
	
	const NETWORK_ID = self::TURTLE;
	
	/** @var float */
	public $width = 0.9, $height = 1.9;
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return "Turtle";
	}
	
	/**
	 * @return Array
	 */
	public function getDrops() : Array {
		return [
			
        ];
    }
    
    /**
     * @return Int
     */
    public function getXpDropAmount() : Int {
    	return mt_rand(3, 10);
    }
}

?>