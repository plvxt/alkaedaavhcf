<?php

namespace alkaedaav\item\netherite;

use alkaedaav\item\Items;

use pocketmine\item\Armor;

class Helmet extends Armor {

    /**
     * Helmet Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(Items::NETHERITE_HELMET, $meta, "Netherite Helmet");
    }

    /**
     * @return Int
     */
    public function getDefensePoints() : Int {
        return 3;
    }

    /**
     * @return Int
     */
    public function getMaxDurability() : Int {
        return 481;
    }
}

?>