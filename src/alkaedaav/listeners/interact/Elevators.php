<?php

namespace alkaedaav\listeners\interact;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\tile\Sign;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

use pocketmine\block\Air;

use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;

class Elevators implements Listener {

    const ELEVATOR_UP = "up", ELEVATOR_DOWN = "down";

    /**
     * Elevators Constructor.
     */
    public function __construct(){
        
    }

    /**
	 * @param SignChangeEvent $event
	 * @return void
	 */
	public function onSignChangeEvent(SignChangeEvent $event) : void {
		if($event->getLine(0) === "[elevator]"){
		    if($event->getLine(1) === "up"||$event->getLine(1) === "Up"||$event->getLine(1) === "down"||$event->getLine(1) === "Down"){
			    $event->setLine(0, TE::RED."[Elevator]");
			    $event->setLine(1, $event->getLine(1));
			}
		}
	}
	
	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onInteractEvent(PlayerInteractEvent $event) : void {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$level = Loader::getInstance()->getServer()->getDefaultLevel();
		$diff = $level->getTileAt($block->getX(), $block->getY(), $block->getZ());
		if($diff instanceof Sign){
			if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
				$line = $diff->getText();
				if($line[0] === TE::RED."[Elevator]"){
					if($line[1] === "up"){
						$this->teleportToSign($player, new Vector3($block->getX(), $block->getY(), $block->getZ()), $this->getSignText($line[1]));
					}elseif($line[1] === "down"){
						$this->teleportToSign($player, new Vector3($block->getX(), $block->getY(), $block->getZ()), $this->getSignText($line[1]));
					}
				}
			}
		}
	}
	
	/**
	 * @param Int $x
	 * @param Int $y
	 * @param Int $z
	 * @return null|Int
	 */
	protected function getTextDown(Int $x, Int $y, Int $z){
		$level = Loader::getInstance()->getServer()->getDefaultLevel();
		for($i = $y - 1; $i >= 0; $i--){
			$pos1 = $level->getBlockAt($x, $i, $z);
			$pos2 = $level->getBlockAt($x, $i + 1, $z);
			if($pos1 instanceof Air && $pos2 instanceof Air){
				return $i;
			}
		}
		return $y;
	}
	
	/**
	 * @param Int $x
	 * @param Int $y
	 * @param Int $z
	 * @return null|Int
	 */
	protected function getTextUp(Int $x, Int $y, Int $z){
		$level = Loader::getInstance()->getServer()->getDefaultLevel();
		for($i = $y + 1; $i <= 256; $i++){
			$pos1 = $level->getBlockAt($x, $i, $z);
			$pos2 = $level->getBlockAt($x, $i + 1, $z);
			if($pos1 instanceof Air && $pos2 instanceof Air){
				return $i;
			}
		}
		return $y;
	}
	
	/**
	 * @param Int $x
	 * @param Int $y
	 * @param Int $z
	 * @return bool
	 */
	protected function isSign(String $signType, Int $x, Int $y, Int $z) : bool {
		$default = false;
		$level = Loader::getInstance()->getServer()->getDefaultLevel();
		if($signType === self::ELEVATOR_UP){
			for($i = $y + 1; $i <= 256; $i++){
				$pos1 = $level->getBlockAt($x, $i, $z);
				$pos2 = $level->getBlockAt($x, $i + 1, $z);
				if($pos1 instanceof Air && $pos2 instanceof Air){
					$default = true;
				}
			}
		}elseif($signType === self::ELEVATOR_DOWN){
			for($i = $y - 1; $i >= 0; $i--){
				$pos1 = $level->getBlockAt($x, $i, $z);
				$pos2 = $level->getBlockAt($x, $i + 1, $z);
				if($pos1 instanceof Air && $pos2 instanceof Air){
					$default = true;
				}
			}
		}
		return $default;
	}
	
	/**
	 * @param String $singType
	 * @return null|String
	 */
	protected function getSignText(String $signType) : ?String {
		if($signType === "up"){
			return self::ELEVATOR_UP;
		}
		if($signType === "down"){
			return self::ELEVATOR_DOWN;
		}
		return self::ELEVATOR_UP;
	}
	
	/**
	 * @param Player $player
	 * @param Vector3 $position
	 * @param String $signType
	 */
	protected function teleportToSign(Player $player, Vector3 $position, String $signType = self::ELEVATOR_UP){
		if($this->isSign($signType, $position->getX(), $position->getY(), $position->getZ())){
			if($signType === self::ELEVATOR_UP){
				$location = $this->getTextUp($position->getX(), $position->getY(), $position->getZ());
				$player->teleport(new Vector3($position->getX() + 0.5, $location, $position->getZ() + 0.5, $player->getLevel()));
			}elseif($signType === self::ELEVATOR_DOWN){
				$location = $this->getTextDown($position->getX(), $position->getY(), $position->getZ());
				$player->teleport(new Vector3($position->getX() + 0.5, $location, $position->getZ() + 0.5, $player->getLevel()));
			}
		}
	}
}

?>