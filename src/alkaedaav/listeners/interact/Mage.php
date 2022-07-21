<?php

namespace alkaedaav\listeners\interact;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use alkaedaav\player\Player;
use alkaedaav\Loader;
use alkaedaav\Factions;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;

class Mage implements Listener {
    
    /**
	 * Mage Constructor.
	 */
	public function __construct(){

    }
    
    /**
     * @param ProjectileHitEvent $event
     * @return void
     */
    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();

        if($player->isMageClass()) {
            if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR || $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                if(Factions::isSpawnRegion($player)) {
                    $event->setCancelled(true);
                    return;
                }
                    switch($event->getItem()->getID()){
                        case ItemIds::SPIDER_EYE:
                            if($player->getMageEnergy() < $player->getMageEnergyCost($event->getItem()->getId())){
                                $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("mage_not_enough_energy")));
                                return;
                            }
                            $player->setMageEnergy($player->getMageEnergy() - $player->getMageEnergyCost($event->getItem()->getId()));
                            $event->getItem()->setCount($event->getItem()->getCount() - 1);
                            $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
                            $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}","{effect}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId()),"Wither"], Loader::getConfiguration("messages")->get("mage_use_spell")));
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer){
                                if($onlinePlayer->distanceSquared($player) < 50){
                                	if($onlinePlayer->getName() === $player->getName()){
                                		
                                	}else{
                                    if($onlinePlayer->getRegion() === "Spawn"){

                                    }else{
                                        if(Factions::inFaction($player->getName())){
                                            if(Factions::getFaction($player) === Factions::getFaction($onlinePlayer->getName())){

                                            }else{
                                                $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::WITHER), 20 * 15, 2));
                                            }
                                        }
                                        $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::WITHER), 20 * 15,2 ));
                                    }
                                }
                            }
                            }
                            break;

                        case ItemIds::COAL:
                            if($player->getMageEnergy() < $player->getMageEnergyCost($event->getItem()->getId())){
                                $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("mage_not_enough_energy")));
                                return;
                            }
                            $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}", "{effect}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId()), "Weakness"], Loader::getConfiguration("messages")->get("mage_give_effects")));
                            $player->setMageEnergy($player->getMageEnergy() - $player->getMageEnergyCost($event->getItem()->getId()));
                            $event->getItem()->setCount($event->getItem()->getCount() - 1);
                            $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer){
                                if($onlinePlayer->distanceSquared($player) < 50){
                                	if($onlinePlayer->getName() === $player->getName()){
                                		
                                	}else{
                                    if($onlinePlayer->getRegion() === "Spawn"){

                                    }else{
                                        if(Factions::inFaction($player->getName())){
                                            if(Factions::getFaction($player) === Factions::getFaction($onlinePlayer->getName())){

                                            }else{
                                                $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::WEAKNESS), 20 * 35,2 ));
                                            }
                                        }
                                        $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::WEAKNESS), 20 * 35,2 ));
                                    }
                                }
                            }
                            }
                            break;
                        case ItemIds::ROTTEN_FLESH:
                            if($player->getMageEnergy() < $player->getMageEnergyCost($event->getItem()->getId())){
                                $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("mage_not_enough_energy")));
                                return;
                            }
                            $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}", "{effect}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId()), "Hunger"], Loader::getConfiguration("messages")->get("mage_give_effects")));
                            $player->setMageEnergy($player->getMageEnergy() - $player->getMageEnergyCost($event->getItem()->getId()));
                            $event->getItem()->setCount($event->getItem()->getCount() - 1);
                            $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer){
                                if($onlinePlayer->distanceSquared($player) < 50){
                                	if($onlinePlayer->getName() === $player->getName()){
                                		
                                		
                                	}else{
                                    if($onlinePlayer->getRegion() === "Spawn"){

                                    }else{
                                        if(Factions::inFaction($player->getName())){
                                            if(Factions::getFaction($player) === Factions::getFaction($onlinePlayer->getName())){

                                            }else{
                                                $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::HUNGER), 29 * 10,2 ));
                                            }
                                        }
                                        $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::HUNGER), 20 * 10,2 ));
                                    }
                                }
                                }
                            }
                            break;
                        case ItemIds::DYE:
                            if($player->getMageEnergy() < $player->getMageEnergyCost($event->getItem()->getId())){
                                $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("mage_not_enough_energy")));
                                return;
                            }
                            $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}","{effect}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId()), "Poison"], Loader::getConfiguration("messages")->get("mage_give_effects")));
                            $player->setMageEnergy($player->getMageEnergy() - $player->getMageEnergyCost($event->getItem()->getId()));
                            $event->getItem()->setCount($event->getItem()->getCount() - 1);
                            $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer){
                                if($onlinePlayer->distanceSquared($player) < 50){
                                	if($onlinePlayer->getName() === $player->getName()){
                                		
                                	}else{
                                    if($onlinePlayer->getRegion() === "Spawn"){

                                    }else{
                                        if(Factions::inFaction($player->getName())){
                                            if(Factions::getFaction($player) === Factions::getFaction($onlinePlayer->getName())){

                                            }else{
                                                $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::POISON), 20 * 15,2 ));
                                            }
                                        }
                                        $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::POISON), 20 * 15,2 ));
                                    }
                                }
                            }
                            }
                            break;
                        case ItemIds::SEEDS:
                            if($player->getMageEnergy() < $player->getMageEnergyCost($event->getItem()->getId())){
                                $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("mage_not_enough_energy")));
                                return;
                            }
                            $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}", "{effect}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId()), "Nausea"], Loader::getConfiguration("messages")->get("mage_give_effects")));
                            $player->setMageEnergy($player->getMageEnergy() - $player->getMageEnergyCost($event->getItem()->getId()));
                            $event->getItem()->setCount($event->getItem()->getCount() - 1);
                            $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer){
                                if($onlinePlayer->distanceSquared($player) < 50){
                                	if($player->getName() === $onlinePlayer->getName()){
                                		
                                	}else{
                                    if($onlinePlayer->getRegion() === "Spawn"){

                                    }else{
                                        if(Factions::inFaction($player->getName())){
                                            if(Factions::getFaction($player) === Factions::getFaction($onlinePlayer->getName())){

                                            }else{
                                                $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::NAUSEA), 20 * 25,2 ));
                                            }
                                        }
                                        $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::NAUSEA), 20 * 25,2 ));
                                    }
                                }
                                }
                            }
                            break;
                        case ItemIds::GOLD_NUGGET:
                            if($player->getMageEnergy() < $player->getMageEnergyCost($event->getItem()->getId())){
                                $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId())], Loader::getConfiguration("messages")->get("mage_not_enough_energy")));
                                return;
                            }
                            $player->sendMessage(str_replace(["&", "{currentEnergy}", "{needEnergy}", "{effect}"], ["§", $player->getMageEnergy(), $player->getMageEnergyCost($event->getItem()->getId()), "Slowness"], Loader::getConfiguration("messages")->get("mage_give_effects")));
                            $player->setMageEnergy($player->getMageEnergy() - $player->getMageEnergyCost($event->getItem()->getId()));
                            $event->getItem()->setCount($event->getItem()->getCount() - 1);
                            $player->getInventory()->setItemInHand($event->getItem()->getCount() > 0 ? $event->getItem() : Item::get(Item::AIR));
                            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer){
                                if($onlinePlayer->distanceSquared($player) < 50){
                                	if($onlinePlayer->getName() === $player->getName()){
                                		
                                	}else{
                                    if($onlinePlayer->getRegion() === "Spawn"){

                                    }else{
                                        if(Factions::inFaction($player->getName())){
                                            if(Factions::getFaction($player) === Factions::getFaction($onlinePlayer->getName())){

                                            }else{
                                                $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 * 15, 1));
                                            }
                                        }
                                        $onlinePlayer->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 20 * 15, 1));
                                    }
                                }
                            }
                            }
                            break;
                }
            }
        }
    }
}