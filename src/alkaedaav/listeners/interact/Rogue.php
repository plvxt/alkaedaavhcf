<?php
    
namespace alkaedaav\listeners\interact;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use alkaedaav\Task\specials\RogueTask;

use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};

class Rogue implements Listener {
	
	/**
	 * Rogue Constructor.
	 */
	public function __construct(){

    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onDamage(EntityDamageEvent $event){
      if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            $damager = $event->getDamager();
            if($entity instanceof Player && $damager instanceof Player){
               if($damager->isRogueClass()){
                  if($damager->getInventory()->getItemInHand()->getId() == Item::GOLD_SWORD){
                    if(!isset(Loader::$rogue[$damager->getName()])){
                     $damager->getInventory()->setItemInHand(Item::get(Item::AIR));
                     $heart = $entity->getHealth();
                     $damage = mt_rand(4, 6);
                     $entity->setHealth($heart - $damage);
                     Loader::$rogue[$damager->getName()] = time() + 15;
                     $damager->setRogueItem(true);
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new RogueTask($damager), 20);
                    }else if(time() < Loader::$rogue[$damager->getName()]){
                      return;
                    }else{
                      unset(Loader::$rogue[$damager->getName()]);
                    }
                  }
                }
            }
        }
    }
}