<?php

namespace alkaedaav\API\InvMenu\type;

use pocketmine\inventory\ContainerInventory;
use alkaedaav\API\InvMenu\InvHandler;

use pocketmine\Player;
use pocketmine\block\{Block, BlockIds};
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\tile\{Tile, EnderChest};
use pocketmine\level\Position;
use pocketmine\math\Vector3;

use pocketmine\nbt\tag\{CompoundTag, IntTag, StringTag};

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class EnderChestInventory extends \pocketmine\inventory\EnderChestInventory {

    /** @var Position */
    protected $holder;

    /**
     * EnderChestInventory Constructor.
     */
    public function __construct(){
        parent::__construct(new Position());
    }

    /**
     * @param Player $player
     * @return void
     */
public function openInventory(Player $player) : void {
    $position = new Position((int)$player->x, (int)($player->y + 5), (int)$player->z, $player->getLevel());

    $this->holder = $position;

    $nbt = new CompoundTag("", [
        new StringTag(Tile::TAG_ID, Tile::CHEST),
        new IntTag(Tile::TAG_X, (int)$position->x),
        new IntTag(Tile::TAG_Y, (int)$position->y),
        new IntTag(Tile::TAG_Z, (int)$position->z),
    ]);
    $chest = Tile::createTile(Tile::ENDER_CHEST, $player->getLevel(), $nbt);

    $pk = new UpdateBlockPacket();
    $pk->x = (int)$position->x;
    $pk->y = (int)$position->y;
    $pk->z = (int)$position->z;
    $pk->flags = UpdateBlockPacket::FLAG_ALL;
    $pk->blockRuntimeId = Block::get(Block::ENDER_CHEST)->getRuntimeId();
    $player->dataPacket($pk);

    $player->getEnderChestInventory()->setHolderPosition($chest);
    $player->addWindow($player->getEnderChestInventory());
}

}

?>
