<?php

namespace alkaedaav\item;

use alkaedaav\item\specials\{Firework,
    NinjaShear,
    StormBreaker,
    AntiTrapper,
    EggPorts,
    Strength,
    Resistance,
    Invisibility,
    PotionCounter,
    PrePearl,
    CloseCall,
    RemovePearl,
    Cactus,
    TankMode,
    RageMode,
    RageBrick,
    MediKit,
    ZombieBardItem,
    Sky,
    GraplingHook,
    HolyClocks};

use pocketmine\item\{Item, ItemFactory};

class Items {
	/**
	 * @return void
	 */
	public static function init() : void {
		ItemFactory::registerItem(new EnderPearl(), true);
		ItemFactory::registerItem(new FishingRod(), true);
		ItemFactory::registerItem(new SplashPotion(), true);
		ItemFactory::registerItem(new GoldenApple(), true);
		ItemFactory::registerItem(new GoldenAppleEnchanted(), true);
		ItemFactory::registerItem(new EnderEye(), true);
		ItemFactory::registerItem(new StormBreaker(), true);
		ItemFactory::registerItem(new AntiTrapper(), true);
		ItemFactory::registerItem(new EggPorts(), true);
		ItemFactory::registerItem(new Strength(), true);
		ItemFactory::registerItem(new Resistance(), true);
		ItemFactory::registerItem(new Invisibility(), true);
		ItemFactory::registerItem(new PotionCounter(), true);
		ItemFactory::registerItem(new Firework(), true);
		ItemFactory::registerItem(new Cactus(), true);
		ItemFactory::registerItem(new CloseCall(), true);
		ItemFactory::registerItem(new RemovePearl(), true);
		ItemFactory::registerItem(new RemovePearl(), true);
		ItemFactory::registerItem(new RageMode(), true);
		ItemFactory::registerItem(new RageBrick(), true);
		ItemFactory::registerItem(new TankMode(), true);
		ItemFactory::registerItem(new MediKit(), true);
		ItemFactory::registerItem(new ZombieBardItem(), true);
		ItemFactory::registerItem(new Sky(), true);
		ItemFactory::registerItem(new GraplingHook(), true);
		ItemFactory::registerItem(new HolyClocks(), true);
		ItemFactory::registerItem(new NinjaShear(), true);

		Item::initCreativeItems();
	}

	/**
	 * @param Item $item
	 * @return Array[]
	 */
	public static function itemSerialize(Item $item) : Array {
		$data = $item->jsonSerialize();
		return $data;
	}

	/**
	 * @param Array $items
	 * @return Item
	 */
	public static function itemDeserialize(Array $items) : Item {
		$item = Item::jsonDeserialize($items);
		return $item;
	}
}

?>