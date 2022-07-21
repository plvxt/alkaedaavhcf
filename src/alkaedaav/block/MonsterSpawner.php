<?php

namespace alkaedaav\block;

use alkaedaav\Loader;
use alkaedaav\entities\tiles\MonsterTileSpawner;

use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\tile\Tile;

use pocketmine\block\{Block, Transparent, BlockToolType};
use pocketmine\item\{Item, Tool, TieredTool};
use pocketmine\nbt\tag\{CompoundTag, IntTag, StringTag};

class MonsterSpawner extends Transparent {
	
	/**
	 * MonsterSpawner Constructor.
	 * @param Int $meta
	 */
	public function __construct(Int $meta = 0){
		parent::__construct(self::MONSTER_SPAWNER, $meta, "Monster Spawner");
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
	public function getToolHarvestLevel() : Int {
		return TieredTool::TIER_WOODEN;
	}
	
	/**
	 * @return Array[]
	 */
	public function getDropsForCompatibleTool(Item $item) : Array {
		return [];
	}

	/**
	 * @return bool
	 */
	public function isAffectedBySilkTouch() : bool {
		return false;
	}
	
	/**
	 * @param Item $item
	 * @param Block $blockReplace
	 * @param Block $blockClicked
	 * @param Int $face
	 * @param Vector3 $clickVector
	 * @param Player $player
	 * @return bool
	 */
	public function place(Item $item, Block $blockReplace, Block $blockClicked, Int $face, Vector3 $clickVector,Player $player = null) : bool {
		$this->getLevel()->setBlock($blockReplace, $this, true, true);
		$nbt = new CompoundTag("", [
		    new StringTag(Tile::TAG_ID, Tile::MOB_SPAWNER),
			new IntTag(Tile::TAG_X, $this->x),
			new IntTag(Tile::TAG_Y, $this->y),
			new IntTag(Tile::TAG_Z, $this->z),
		]);
		$tile = $this->getLevel()->getTile($this);
		if(!$tile instanceof MonsterTileSpawner){
			$tile = Tile::createTile("MonsterTileSpawner", $this->getLevel(), $nbt);
		}
		$tile->setName($item->getCustomName());
		$tile->spawnToAll();
		return true;
	}
	
	/**
	 * @return Int
	 */
	protected function getXpDropAmount() : Int {
		return mt_rand(15, 43);
	}
}

?>