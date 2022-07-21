<?php

namespace alkaedaav\item\specials;

use pocketmine\event\entity\ProjectileHitEntityEvent;
use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;

class EggPorts extends CustomProjectileItem {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * EggPorts Constructor.
	 */
	public function __construct(){
		parent::__construct(self::EGG, TE::AQUA.TE::BOLD."EggPort", [TE::GREEN.TE::BOLD."RARE ITEM".TE::RESET."\n\n".TE::GRAY."Can change position in a radius of 7 blocks"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 16;
	}
	
	/**
	 * @return String
	 */
	public function getProjectileEntityType() : String {
		return "Egg";
	}
	
	/**
	 * @return float
	 */
	public function getThrowForce() : float {
        return 2.0;
	}

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     */
    public function onClickAir(\pocketmine\Player $player, \pocketmine\math\Vector3 $directionVector) : bool {
        return true;
    }
}

?>