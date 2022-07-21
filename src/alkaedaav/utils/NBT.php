<?php

namespace alkaedaav\utils;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

use pocketmine\nbt\tag\{FloatTag, CompoundTag, DoubleTag, ListTag, ShortTag};

class NBT {

    /**
     * @param float $x
     * @param float $y
     * @param float $z
     * @param float $yaw
     * @param float $pitch
     * @return CompoundTag
     */
    public static function createNBT(float $x, float $y, float $z, float $yaw = 0.0, float $pitch = 0.0) : CompoundTag {
        $nbt = new CompoundTag("", [
            new ListTag("Pos", [
            new DoubleTag("", $x),
            new DoubleTag("", $y),
            new DoubleTag("", $z),
            ]),
            new ListTag("Motion", [
            new DoubleTag("", -sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI)),
            new DoubleTag("", -sin($pitch / 180 * M_PI)),
            new DoubleTag("", cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI)),
            ]),
            new ListTag("Rotation", [
            new FloatTag("", $yaw),
            new FloatTag("", $pitch),
            ]),
        ]);
        return $nbt;
    }
	
	/**
	 * @param Player $player
	 * @return CompoundTag
	 */
	public static function createWith(Player $player) : CompoundTag {
		$nbt = new CompoundTag("", [
            new ListTag("Pos", [
            new DoubleTag("", $player->x),
            new DoubleTag("", $player->y + $player->getEyeHeight()),
            new DoubleTag("", $player->z),
            ]),
            new ListTag("Motion", [
            new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
            new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
            new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
            ]),
            new ListTag("Rotation", [
            new FloatTag("", $player->yaw),
            new FloatTag("", $player->pitch),
            ]),
        ]);
        return $nbt;
	}
}

?>