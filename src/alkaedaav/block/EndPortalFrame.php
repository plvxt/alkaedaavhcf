<?php

namespace alkaedaav\block;

use alkaedaav\item\EnderEye;

use pocketmine\Player;

use pocketmine\block\{Block, Solid};
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class EndPortalFrame extends Solid {
	
	/**
	 * EndPortalFrame Constructor.
	 * @param Int $meta
	 */
	public function __construct(Int $meta = 0){
		parent::__construct(self::END_PORTAL_FRAME, $meta, "End Portal Frame");
    }

    /**
     * @return Int
     */
    public function getLightLevel() : Int {
        return 5;
    }

    /**
     * @return float
     */
    public function getHardness() : float {
        return -1;
    }

    /**
     * @return float
     */
    public function getBlastResistance() : float {
        return 1800000;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isBreakable(Item $item) : bool {
        return false;
    }

    /**
     * @param Item $item
     * @param Player $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null) : bool {
        if(($this->getDamage() & 0x04) === 0 && $player !== null && $item instanceof EnderEye){
            $this->setDamage($this->getDamage() + 4);
            $this->getLevel()->setBlock($this, $this, true, true);
            return true;
        }
        return false;
    }
}

?>