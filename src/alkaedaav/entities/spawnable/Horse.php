<?php

namespace alkaedaav\entities\spawnable;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Horse extends Monster {
	
	const NETWORK_ID = self::HORSE;
	
	/** @var float */
	public $width = 1.0, $height = 1.6;
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return "Horse";
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