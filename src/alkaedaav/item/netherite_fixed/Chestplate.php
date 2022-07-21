<?php

namespace alkaedaav\item\netherite;

use alkaedaav\item\Items;

use pocketmine\item\Armor;

class Chestplate extends Armor {

    /**
     * Chestplate Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(Items::NETHERITE_CHESTPLATE, $meta, "Netherite Chestplate");
    }

    /**
     * @return Int
     */
    public function getDefensePoints() : Int {
        return 8;
    }

    /**
     * @return Int
     */
    public function getMaxDurability() : Int {
        return 592;
    }
}

?>