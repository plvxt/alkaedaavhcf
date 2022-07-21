<?php

namespace alkaedaav\listeners\interact;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\utils\Time;

use alkaedaav\Task\{GappleTask, GoldenGappleTask};
use alkaedaav\item\{GoldenApple, GoldenAppleEnchanted};

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

use pocketmine\event\player\PlayerItemConsumeEvent;

class Gapple implements Listener {
	
	/**
	 * Gapple Constructor.
	 */
	public function __construct(){
		if(!empty(Loader::$appleenchanted)){
			foreach(Loader::$appleenchanted as $name => $name){
				Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new GappleTask($name), 20);
			}
		}
	}
	
	/**
	 * @param PlayerItemConsumeEvent $event
	 * @return void
	 */
	public function onPlayerItemConsumeEvent(PlayerItemConsumeEvent $event) : void {
		$player = $event->getPlayer();
		$item = $event->getItem();
		if($item->getId() === ItemIds::APPLEENCHANTED){
			if(self::isGappleCooldown($player->getName())){
				$player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTime(Loader::$appleenchanted[$player->getName()]["time"])], Loader::getConfiguration("messages")->get("apple_cooldown")));
				$event->setCancelled(true);
			}else{
				self::addGappleCooldown($player->getName());
				Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new GappleTask($player->getName()), 20);
			}
		}
		if($item->getId() === ItemIds::GOLDEN_APPLE){
			if($player->isGoldenGapple()){
				$player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getGoldenAppleTime())], Loader::getConfiguration("messages")->get("apple_cooldown")));
				$event->setCancelled(true);
				return;
			}
			$player->setGoldenApple(true);
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new GoldenGappleTask($player), 20);
		}
	}
	
	/**
	 * @param String $playerName|null
	 * @return void
	 */
	public static function addGappleCooldown(?String $playerName){
		if(!isset(Loader::$appleenchanted[$playerName])){
			Loader::$appleenchanted[$playerName] = ["time" => time() + (1 * 3600)];
		}
	}
	
	/**
	 * @param PlayerClass $player
	 * @return void
	 */
	public static function removeGappleCooldown(?String $playerName){
		if(isset(Loader::$appleenchanted[$playerName])){
			unset(Loader::$appleenchanted[$playerName]);
		}
	}
	
	/**
	 * @param String $playerName|null
	 * @return bool
	 */
	public static function isGappleCooldown(?String $playerName) : bool {
		if(isset(Loader::$appleenchanted[$playerName])){
			if(Loader::$appleenchanted[$playerName]["time"] < time()){
				self::removeGappleCooldown($playerName);
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
		return false;
	}
}