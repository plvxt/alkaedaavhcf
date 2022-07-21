<?php

namespace alkaedaav\listeners\interact;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use alkaedaav\utils\Translator;

use alkaedaav\Task\ArcherTagTask;

use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;
use pocketmine\entity\projectile\Arrow;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\{ProjectileHitEvent, ProjectileHitEntityEvent, EntityDamageEvent, EntityDamageByEntityEvent};

class Archer implements Listener {
	
	/**
	 * Archer Constructor.
	 */
	public function __construct(){

    }
    
    /**
     * @param ProjectileHitEvent $event
     * @return void
     */
    public function onProjectileHitEvent(ProjectileHitEvent $event) : void {
        $entity = $event->getEntity();
        if($entity instanceof Arrow){
            $damager = $entity->getOwningEntity();
            if($damager instanceof Player && $event instanceof ProjectileHitEntityEvent && $damager->isArcherClass()){
                $player = $event->getEntityHit();
                if($player instanceof Player && !Factions::isSpawnRegion($damager) && !Factions::isSpawnRegion($player)){
                    if($player->isArcherTag()){
                    	$player->setArcherTag(false);
                    }
                    if($player->getName() === $damager->getName()) return;

                    if(Factions::inFaction($damager->getName()) && Factions::inFaction($player->getName()) && Factions::getFaction($damager->getName()) === Factions::getFaction($player->getName())) return;

                    $damager->sendMessage(str_replace(["&", "{playerName}", "{playerHealth}"], ["§", $player->getName(), $player->getHealth()], Loader::getConfiguration("messages")->get("archer_tag_mark_target")));
                    $player->setArcherTag(true);
                    $player->setNameTag(str_replace($player->getDisplayName(), TE::YELLOW . $player->getDisplayName(), $player->getNameTag()));
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new ArcherTagTask($player), 20);
                }
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent && !$event->isCancelled()){
            $damager = $event->getDamager();
            if($player instanceof Player and $damager instanceof Player){
                if($player->isArcherTag()){
                    $baseDamage = $event->getBaseDamage();
                    $event->setBaseDamage($baseDamage + 2.0);
                }
            }
        }
    }
	
	/**
     * @param PlayerInteractEvent $event
     * @return void
     */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        $item = $event->getItem();
		if($player->isArcherClass()){
			if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR||$event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
				switch($item->getId()){
					case ItemIds::SUGAR:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getArcherEnergy() < $player->getBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getArcherEnergy(), $player->getBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("archer_not_enough_energy")));
							return;
                        }
                        $effect = new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 10, 3);

						$player->setArcherEnergy($player->getArcherEnergy() - $player->getBardEnergyCost($item->getId()));
                        $player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("archer_give_effects")));


                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
					break;
					case ItemIds::FEATHER:
						if(Factions::isSpawnRegion($player)){
							$event->setCancelled(true);
							return;
						}
						if($player->getArcherEnergy() < $player->getBardEnergyCost($item->getId())){
							$player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getArcherEnergy(), $player->getBardEnergyCost($item->getId())], Loader::getConfiguration("messages")->get("archer_not_enough_energy")));
							return;
                        }
                        $effect = new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 20 * 10, 3);

						$player->setArcherEnergy($player->getArcherEnergy() - $player->getBardEnergyCost($item->getId()));
                        $player->addEffect($effect);
                        $player->sendMessage(str_replace(["&", "{effectName}", "{effectLevel}"], ["§", Translator::effectToStringByObject($effect), $effect->getAmplifier()], Loader::getConfiguration("messages")->get("archer_give_effects")));

                        $item->setCount($item->getCount() - 1);
                        $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
					break;
				}
			}
		}
	}
}

?>