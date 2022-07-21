<?php

namespace alkaedaav\listeners\event;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\Server;
use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\Task\event\EOTWTask;

use pocketmine\event\Listener;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class EOTW implements Listener {
	
	/** @var bool */
	protected static $enable = false;
	
	/** @var Int */
	protected static $time;

	/** @var Player[] */
	protected static $death_players = [];
	
	/**
	 * EOTW Constructor.
	 */
	public function __construct(){

	}
	
	/**
	 * @return bool
	 */
	public static function isEnable() : bool {
		return self::$enable;
	}
	
	/**
	 * @param bool $enable
	 */
	public static function setEnable(bool $enable){
		self::$enable = $enable;
	}
	
	/**
	 * @param Int $time
	 */
	public static function setTime(Int $time){
		self::$time = $time;
	}
	
	/**
	 * @return Int
	 */
	public static function getTime() : Int {
		return self::$time;
	}
	
	/**
	 * @param Int $time
	 * @return void
	 */
	public static function start(Int $time = 60) : void {
		self::setEnable(true);
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new EOTWTask($time), 20);
		foreach(Server::getInstance()->getOnlinePlayers() as $player) {
		    if($player instanceof Player) {
		        $player->setInvincibility(false);
            }
        }
	}
	
	/**
	 * @return void
	 */
	public static function stop() : void {
		self::setEnable(false);
	}

    /**
     * @param PlayerDeathEvent $event
     * @priority HIGHEST
     */
	public function onDeath(PlayerDeathEvent $event): void {
	    if(self::isEnable()) {
            $event->getPlayer()->kick("Death in EOTW", false);
        }
    }
}

?>