<?php

namespace alkaedaav\entities\spawnable;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

class Cow extends Monster {
	
	const NETWORK_ID = self::COW;
	
	/** @var float */
	public $width = 0.9, $height = 1.5;
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return "Cow";
	}
	
	/**
	 * @return Array
	 */
	public function getDrops() : Array {
		return [
			Item::get(Item::STEAK, 0, mt_rand(1, 3)),
            Item::get(Item::LEATHER, 0, mt_rand(0, 2)),
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