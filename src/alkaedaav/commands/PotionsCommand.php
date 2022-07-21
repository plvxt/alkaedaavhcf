<?php

namespace alkaedaav\commands;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use alkaedaav\utils\Time;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};

use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\tile\{Tile, Chest};
use pocketmine\level\Position;
use pocketmine\item\{Item, ItemIds};

class PotionsCommand extends PluginCommand {
	
	/**
	 * BrewerCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("pots", Loader::getInstance());
		
		parent::setDescription("Can get six chests full of potions");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(Factions::getRegionName($sender) !== Factions::getFaction($sender->getName())){
			$sender->sendMessage(TE::RED."You can only execute this command in your claim!");
			return;
		}
		if($sender->getTimeBrewerRemaining() < time()){
			$position = $sender->getPosition();
			/** TODO: 4 variables are implemented to make the combination of chests */
			$tile1 = self::createTileChest($position);
		
			$tile2 = self::createTileChest(new Position($position->x + 1, $position->y, $position->z, $position->getLevel()));
		
			$tile3 = self::createTileChest(new Position($position->x, $position->y + 1, $position->z, $position->getLevel()));
		
			$tile4 = self::createTileChest(new Position($position->x + 1, $position->y + 1, $position->z, $position->getLevel()));

			$tile5 = self::createTileChest(new Position($position->x, $position->y + 2, $position->z, $position->getLevel()));

			$tile6 = self::createTileChest(new Position($position->x + 1, $position->y + 2, $position->z, $position->getLevel()));

		
			/** @var ItemIds */
			for($index = 0; $index <= 26; $index++){
				$tile1->getInventory()->setItem($index, Item::get(ItemIds::SPLASH_POTION, 22, 1));
				$tile2->getInventory()->setItem($index, Item::get(ItemIds::SPLASH_POTION, 22, 1));
				$tile3->getInventory()->setItem($index, Item::get(ItemIds::SPLASH_POTION, 22, 1));
				$tile4->getInventory()->setItem($index, Item::get(ItemIds::POTION, 16, 1));
				$tile5->getInventory()->setItem($index, Item::get(ItemIds::POTION, 13, 1));
				$tile6->getInventory()->setItem($index, Item::get(ItemIds::POTION, 8, 1));

			}
			$sender->resetBrewerTime();
		}else{
			$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeBrewerRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
        }
	}
	
	/**
	 * @param Position $position
	 * @return Tile
	 */
	protected static function createTileChest(Position $position) : Tile {
		try {
			$chest = Tile::createTile("Chest", $position->getLevel(), Chest::createNBT($position));
			$position->getLevel()->setBlock(new Vector3($chest->getX(), $chest->getY(), $chest->getZ()), Block::get(Block::CHEST));
			return $chest;
		} catch (\Exception $exception) {
			Loader::getInstance()->getLogger()->error($exception->getMessage());
		}
	}
}

?>