<?php

namespace alkaedaav\listeners;

use alkaedaav\Loader;
use alkaedaav\Factions;
use alkaedaav\player\Player;

use alkaedaav\Task\asynctask\RoollbackData;

use pocketmine\event\Listener;
use pocketmine\entity\Entity;

use pocketmine\utils\{Config, TextFormat as TE};

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class Death implements Listener {
	
	/**
	 * Death Constructor.
	 */
	public function __construct(){
		
	}
	
	/**
	 * @param PlayerDeathEvent $event
	 * @return void
	 */
	public function onPlayerDeathEvent(PlayerDeathEvent $event) : void {
		$player = $event->getPlayer();
		if($player instanceof Player){
			if($player->getLastDamageCause() instanceof EntityDamageByEntityEvent){
				$damager = $player->getLastDamageCause()->getDamager();
				if($damager instanceof Player){
				  
					$damager->addKills();
					if(Factions::inFaction($damager->getName())){
                Factions::addPoints(Factions::getFaction($damager->getName()), 1);
				
				if (($factionName = Factions::getFaction($player->getName())) == null) {
            return;
        }

        if (Factions::getStrength($factionName) == 0) {
            if (($targetFaction = Factions::getFaction($damager->getName())) == null) {
                return;
            }

            Factions::addPoints($targetFaction, 10);
        }
					}
				}
				Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
				$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::GRAY."[".TE::YELLOW.$player->getKills().TE::GRAY."]".TE::GRAY."[".TE::RED.$player->getHealth().TE::GRAY."]".TE::YELLOW." was killed by ".TE::RESET.TE::RED.$damager->getName().TE::GRAY."[".TE::YELLOW.$damager->getKills().TE::GRAY."]".TE::GRAY."[".TE::RED.$damager->getHealth().TE::GRAY."]".TE::YELLOW." using ".TE::AQUA.$damager->getInventory()->getItemInHand()->getName());
			}else{
			    if($player->getLastDamageCause() === null) return;
				if($player->getLastDamageCause()->getCause() === null) return;
				switch($player->getLastDamageCause()->getCause()){
					case EntityDamageEvent::CAUSE_FALL:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::GOLD." fell from the Burj khalifa!");
					break;
					case EntityDamageEvent::CAUSE_DROWNING:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." drowned, him isn't a fish!");
					break;
					case EntityDamageEvent::CAUSE_FIRE:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." Died burned!");
					break;
					case EntityDamageEvent::CAUSE_FIRE_TICK:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." died burned!");
					break;
					case EntityDamageEvent::CAUSE_LAVA:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." died in lava!");
					break;
					case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." it seems to explode!");
					break;
					case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." it seems to explode!");
					break;
					case EntityDamageEvent::CAUSE_SUICIDE:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." committed suicide!");
					break;
					case EntityDamageEvent::CAUSE_SUFFOCATION:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." he died suffocated!");
					break;
					case EntityDamageEvent::CAUSE_VOID:
						Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new RoollbackData($player->getName(), $player->getInventory()->getContents(), $player->getArmorInventory()->getContents(), new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."inventory.yml", Config::YAML)));
						$event->setDeathMessage(TE::DARK_RED.$player->getName().TE::YELLOW." fell from the world!");
					break;
				}
			}
		}
	}
}

?>