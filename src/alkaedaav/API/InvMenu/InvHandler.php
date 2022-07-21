<?php

namespace alkaedaav\API\InvMenu;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\Task\delayedtask\SendInventoryDelayed;

use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\{EnderChest, Tile, Chest};

use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\ContainerInventory;

use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

abstract class InvHandler extends ContainerInventory implements WindowTypes {
	
	/** @var Position */
	protected $holder;

    /**
     * InvHandler Constructor.
     * @param Position $holder
     * @param Array $items
     * @param Int $size
     * @param String $title
     */
	public function __construct($holder, Array $items = [], Int $size = null, String $title = null){
	    parent::__construct($holder, $items, $size, $title);
	}
	
	/**
	 * @return Block
	 */
	public function getBlock() : Block {
		return Block::get($this->getBlockId());
	}
	
	/**
	 * @param String $title
	 */
	public function setName(String $title){
		$this->title = $title;
	}
	
	/**
	 * @return String
	 */
	public function getName() : String {
		return $this->title;
	}
	
	/**
	 * @return Int
	 */
	abstract public function getBlockId() : int;
	
	/**
	 * @param Position|Vector3 $holder
	 */
	public function setPosition($holder){
		$this->holder = $holder;
	}
	
	/**
	 * @return Position|Vector3
	 */
	public function getPosition(){
		return $this->holder;
	}

	public function getAdditionCompoundTags(CompoundTag $tag, Vector3 $position) : void {

    }

    public function breakAdditionalBlocks(Player $player, Vector3 $position) : void {

    }

	/**
	 * @param Player $who
	 */
	public function onOpen(\pocketmine\Player $who) : void {
		parent::onOpen($who);
		$this->setPosition($who->floor()->subtract(0, 4));

		//fakeblock
		$pk = new UpdateBlockPacket();
		$pk->x = $this->getPosition()->getFloorX();
		$pk->y = $this->getPosition()->getFloorY();
		$pk->z = $this->getPosition()->getFloorZ();
		$pk->flags = UpdateBlockPacket::FLAG_ALL;
		$pk->blockRuntimeId = $this->getBlock()->getRuntimeId();

        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new SendInventoryDelayed($this, $who), 3);
	}
    
    /**
     * @param Player $who
     * @return void
     */
    public function onClose(\pocketmine\Player $who) : void {
    	parent::onClose($who);
    }
}

?>