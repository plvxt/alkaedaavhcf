<?php

namespace alkaedaav\API\InvMenu\type;

use alkaedaav\API\InvMenu\InvHandler;

use pocketmine\block\{Block, BlockIds};
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\tile\Tile;
use pocketmine\level\Position;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class ChestInventory extends InvHandler {

    /** @var Position */
    protected $holder;

    /**
     * ChestInventory Constructor.
     */
	public function __construct(){
        parent::__construct(new Position());
    }

    /**
     * @return Int
     */
    public function getDefaultSize() : Int {
        return 27;
    }

    /**
     * @return Int
     */
    public function getNetworkType() : Int {
        return WindowTypes::CONTAINER;
    }

    /**
     * @return Int
     */
    public function getBlockId() : Int {
        return BlockIds::CHEST;
    }

    /**
     * @return String
     */
    public function getName() : String {
        return "Chest";
    }

    /**
     * @return Int
     */
    protected function getOpenSound() : Int {
        return LevelSoundEventPacket::SOUND_CHEST_OPEN;
    }

    /**
     * @return Int
     */
    protected function getCloseSound() : Int {
        return LevelSoundEventPacket::SOUND_CHEST_CLOSED;
    }
}

?>