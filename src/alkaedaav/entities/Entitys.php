<?php

namespace alkaedaav\entities;

use alkaedaav\entities\ZombieBard;

use alkaedaav\Loader;

use alkaedaav\entities\tiles\{MonsterTileSpawner};

use alkaedaav\entities\spawnable\{Cow, Enderman, Villager, Pig, Creeper, Turtle, Horse};

use pocketmine\entity\Entity;
use pocketmine\tile\Tile;

class Entitys {
	
	/**
	 * @return void
	 */
	public static function init() : void {
		Entity::registerEntity(Cow::class, true, ["Cow"]);
		Entity::registerEntity(Enderman::class, true, ["Enderman"]);
		Entity::registerEntity(Villager::class, true, ["Villager"]);
		Entity::registerEntity(Pig::class, true, ["Pig"]);
		Entity::registerEntity(Creeper::class, true, ["Creeper"]);
		Entity::registerEntity(Turtle::class, true, ["Turtle"]);
		Entity::registerEntity(Horse::class, true, ["Horse"]);
		Entity::registerEntity(ZombieBard::class, true, ["Zombie"]);
			
		Entity::registerEntity(EnderPearl::class, true, ["EnderPearl"]);
        Entity::registerEntity(Egg::class, true, ["Egg"]);
		Entity::registerEntity(SplashPotion::class, true, ["SplashPotion"]);
		Entity::registerEntity(FishingHook::class, true, ["FishingHook"]);
		
		Tile::registerTile(MonsterTileSpawner::class);
	}
	
	/**
	 * @return array[]
	 */
	public static function getEntitysType() : Array {
		return Loader::getDefaultConfig("Entitys");
	}
}

?>