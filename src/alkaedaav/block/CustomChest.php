<?php

declare(strict_types=1);

namespace alkaedaav\block;

use alkaedaav\Factions;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;

/**
 * Class CustomChest
 * @package alkaedaav\block
 */
class CustomChest extends \pocketmine\block\Chest
{
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3
		];

		$chest = null;
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

		for($side = 2; $side <= 5; ++$side){
			if(($this->meta === 4 or $this->meta === 5) and ($side === 4 or $side === 5)){
				continue;
			}elseif(($this->meta === 3 or $this->meta === 2) and ($side === 2 or $side === 3)){
				continue;
			}
			$c = $this->getSide($side);
			if($c->getId() === $this->id and $c->getDamage() === $this->meta){
				$tile = $this->getLevelNonNull()->getTile($c);
				if($tile instanceof Chest and !$tile->isPaired()){
					$chest = $tile;
					break;
				}
			}
		}
		$this->getLevelNonNull()->setBlock($blockReplace, $this, true, true);
		$tile = Tile::createTile(Tile::CHEST, $this->getLevelNonNull(), Chest::createNBT($this, $face, $item, $player));

		if($chest instanceof Chest and $tile instanceof Chest){
			$subclaim = Factions::getSubclaim($chest->getBlock()->asPosition());
			
			if ($subclaim !== null) {
				if ($player !== null && $subclaim === $player->getName()) {
					$chestRegion = Factions::getRegionName($chest->getBlock());
					$blockRegion = Factions::getRegionName($tile->getBlock());
					
					if ($chestRegion === $blockRegion) {
						Factions::addSubclaim($subclaim, $tile->getBlock()->asPosition());
						
						$chest->pairWith($tile);
			            $tile->pairWith($chest);
					}
				}
		    } else {
			    $chest->pairWith($tile);
			    $tile->pairWith($chest);
			}
		}

		return true;
	}
}