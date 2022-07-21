<?php

namespace alkaedaav\item;

use pocketmine\block\Block;

use pocketmine\Player;
use pocketmine\math\Vector3;

class EnderEye extends \pocketmine\item\Item {

    /**
     * EnderEye Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(\pocketmine\item\Item::ENDER_EYE, $meta, "Ender Eye");
    }

    /**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 64;
    }
}

?>