<?php

namespace alkaedaav\block;

use pocketmine\Player;

use pocketmine\block\{Block, BlockToolType, Transparent};
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class Hopper extends Transparent {

    /**
     * Hopper Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(self::HOPPER_BLOCK, $meta, "Hopper Block");
    }

    /**
	 * @return Int
	 */
	public function getToolType() : Int {
		return BlockToolType::TYPE_PICKAXE;
	}

    /**
     * @return Int
     */
    public function getHardness() : Int {
        return 3;
    }

    /**
     * @return Int 
     */
    public function getResistance() : Int {
        return 24;
    }

    /**
     * @return bool
     */
    public function canBeActivated() : bool {
        return false;
    }
}

?>