<?php

namespace alkaedaav\utils;

use alkaedaav\player\Player;
use alkaedaav\API\System;

use pocketmine\math\Vector3;
use pocketmine\block\Block;

use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class Tower {

    /**
     * @param Vector3 $position
     * @param Player $player
     * @param Block $block
     * @return void
     */
    public static function create(Vector3 $position, Player $player, Block $block) : void {
        for($y = $position->getFloorY() + 1; $y <= $position->getFloorY() + 20; $y++){
            $pk = new UpdateBlockPacket();
			$pk->x = $position->getFloorX();
			$pk->y = (int)$y;
			$pk->z = $position->getFloorZ();
			$pk->flags = UpdateBlockPacket::FLAG_ALL;
			$pk->blockRuntimeId = $block->getRuntimeId();
            $player->dataPacket($pk);
        }
    }
    
    /**
     * @param Player $player
     * @param Int $id
     * @return void
     */
    public static function delete(Player $player, Int $id) : void {
        if(System::isPosition($player, $id)){
            $position = Translator::arrayToVector3(System::$cache[$player->getName()]["position".$id]);
      		self::create($position, $player, Block::get(Block::AIR));
        }
    }
}

?>