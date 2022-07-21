<?php

namespace alkaedaav\listeners\event;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use alkaedaav\Task\event\SOTWTask;

use pocketmine\event\Listener;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class SOTW implements Listener {
	
	/** @var bool */
	protected static $enable = false;
	
	/** @var Int */
	protected static $time = 0;
	
	/**
	 * SOTW Constructor.
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
	 * @return void
	 */
	public static function start(Int $time = 60) : void {
		self::setEnable(true);
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SOTWTask($time), 20);
	}
	
	/**
	 * @return void
	 */
	public static function stop() : void {
		self::setEnable(false);
	}
	
	/**
	 * @param EntityDamageEvent $event
	 * @return void
	 */
	public function onEntityDamageEvent(EntityDamageEvent $event) : void {
		$player = $event->getEntity();
		if(self::isEnable()){
			$event->setCancelled(true);
		}
	}
}

?>