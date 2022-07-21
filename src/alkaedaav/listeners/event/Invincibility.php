<?php

namespace alkaedaav\listeners\event;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\{Player, PlayerBase};

use alkaedaav\utils\Time;

use alkaedaav\Task\event\InvincibilityTask;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerMoveEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class Invincibility implements Listener {
	
	/**
	 * Invincibility Constructor.
	 */
	public function __construct(){

	}
	
	/**
	 * @param PlayerJoinEvent $event
	 * @return void
	 */
	public function onPlayerJoinEvent(PlayerJoinEvent $event) : void {
		$player = $event->getPlayer();
		if(!$player->hasPlayedBefore()){
			PlayerBase::setData($player->getName(), "pvp_time", (1 * 3600));
			$player->setInvincibility(true);
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new InvincibilityTask($player), 20);
		}elseif(PlayerBase::isData($player->getName(), "pvp_time")){
     	    $player->setInvincibility(true);
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new InvincibilityTask($player), 20);
		}
	}
	
	/**
	 * @param PlayerQuitEvent $event
	 * @return void
	 */
	public function onPlayerQuitEvent(PlayerQuitEvent $event) : void {
		$player = $event->getPlayer();
		if($player->isInvincibility()){
			PlayerBase::setData($player->getName(), "pvp_time", $player->getInvincibilityTime());
		}
	}
	
	/**
	 * @param PlayerMoveEvent $event
	 * @return void
	 */
	public function onPlayerMoveEvent(PlayerMoveEvent $event) : void {
		$player = $event->getPlayer();
		if($player->isInvincibility() && Factions::isFactionRegion($player)){
			$player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_enter_zone_with_invincibility")));
			$event->setCancelled(true);
		}
	}
	
	/**
	 * @param EntityDamageEvent $event
	 * @return void
	 */
	public function onEntityDamageEvent(EntityDamageEvent $event) : void {
		$player = $event->getEntity();
		if($player instanceof Human||$player instanceof Player && $player->isInvincibility()){
			$event->setCancelled(true);
		}
		if($event instanceof EntityDamageByEntityEvent){
			$damager = $event->getDamager();
			if(!Factions::isSpawnRegion($damager) && $player instanceof Player && $damager instanceof Player && $damager->isInvincibility()){
				$damager->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_have_invincibility")));
				$event->setCancelled(true);
			}
			if(!Factions::isSpawnRegion($player) && $player instanceof Player && $player->isInvincibility()){
				$damager->sendMessage(str_replace(["&", "{playerName}", "{time}"], ["§", $player->getName(), Time::getTimeToFullString($player->getInvincibilityTime())], Loader::getConfiguration("messages")->get("other_player_have_invincibility")));
				$event->setCancelled(true);
			}
		}
	}
}

?>