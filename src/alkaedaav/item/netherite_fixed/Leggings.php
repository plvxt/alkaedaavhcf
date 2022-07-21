<?php

namespace alkaedaav\item\netherite;

use alkaedaav\item\Items;

use pocketmine\item\Armor;

class Leggings extends Armor {

    /**
     * Leggings Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(Items::NETHERITE_LEGGINGS, $meta, "Netherite Leggings");
    }

    /**
     * @return Int
     */
    public function getDefensePoints() : Int {
        return 6;
    }

    /**
     * @return Int
     */
    public function getMaxDurability() : Int {
        return 555;
    }
}

?>