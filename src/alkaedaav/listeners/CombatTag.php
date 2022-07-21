<?php

namespace alkaedaav\listeners;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use Advanced\Data\PlayerBase;

use alkaedaav\Task\CombatTagTask;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\event\player\{PlayerMoveEvent, PlayerDeathEvent};

class CombatTag implements Listener {

    /**
     * CombatTag Constructor.
     */
    public function __construct(){

    }

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
    public function onPlayerMoveEvent(PlayerMoveEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isCombatTag() && $this->inSpawnBorder($player->getFloorX(), $player->getFloorZ(), $player->getLevel()->getName())){
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerDeathEvent $event
     * @return void
     */
    public function onPlayerDeathEvent(PlayerDeathEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isCombatTag()){
            $player->setCombatTag(false);
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public  function onEntityDamageEvent(EntityDamageEvent $event) : void {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK||$event->getCause() === EntityDamageEvent::CAUSE_PROJECTILE){
                if($player instanceof Player && $damager instanceof Player){
                	
                	if(class_exists("PlayerBase")){
                        if(PlayerBase::isStaff($player)) return;
                    }
                	
                    if(Factions::isSpawnRegion($player)||Factions::isSpawnRegion($damager)||$player->isInvincibility()||$damager->isInvincibility()) return;
                    
                    if($player->isCombatTag()){
                    	$player->setCombatTagTime(Loader::getDefaultConfig("Cooldowns")["CombatTag"]);
                    	return;
                    }
                    if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;
                    $player->setCombatTag(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CombatTagTask($player), 20);
                    
                    if($damager->isCombatTag()){
                    	$damager->setCombatTagTime(Loader::getDefaultConfig("Cooldowns")["CombatTag"]);
                    	return;
                    }
                    if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;
                    $damager->setCombatTag(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CombatTagTask($damager), 20);
                }
            }
        }
    }

    /**
	 * @param Int $x
	 * @param Int $z
	 * @param String $levelName
	 * @return bool
	 */
	protected function inSpawnBorder(Int $x, Int $z, String $levelName = null) : bool {
		$data = Loader::getProvider()->getDataBase()->query("SELECT * FROM zoneclaims WHERE protection = 'Spawn';");
		$result = $data->fetchArray(SQLITE3_ASSOC);
		if(empty($result)) return false;
		return $x >= $result["x1"] && $x <= $result["x2"] && $z >= $result["z1"] && $z <= $result["z2"] && $levelName === $result["level"];
	}
}

?>