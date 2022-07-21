<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace alkaedaav\listeners\interact;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;
use alkaedaav\Factions;
use alkaedaav\listeners\event\SOTW;
use alkaedaav\player\Player;

class Ninja implements Listener {

    public function onFight(EntityDamageByEntityEvent $event): void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if(!$entity instanceof Player or !$damager instanceof Player) {
            return;
        }
        if(!$damager->isNinjaClass()) {
            return;
        }
        if(
            Factions::inFaction($damager_name = $damager->getName()) and
            Factions::inFaction($entity_name = $entity->getName()) and
            Factions::getFaction($damager_name) === Factions::getFaction($entity_name)
        ) {
            return;
        } elseif(Factions::isSpawnRegion($entity) or Factions::isSpawnRegion($damager)) {
            return;
        } elseif(SOTW::isEnable()) {
            return;
        } elseif($entity->isInvincibility() or $damager->isInvincibility()) {
            return;
        }
        $item = $damager->getInventory()->getItemInHand();
        if($item->getId() !== ItemIds::STONE_SWORD) {
            return;
        }
        $damager->addNinjaHit();
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event): void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if(!$entity instanceof Player or !$damager instanceof Player) {
            return;
        }
        if(!$damager->isNinjaClass()) {
            return;
        }
        if(
            Factions::inFaction($damager_name = $damager->getName()) and
            Factions::inFaction($entity_name = $entity->getName()) and
            Factions::getFaction($damager_name) === Factions::getFaction($entity_name)
        ) {
            return;
        } elseif(Factions::isSpawnRegion($entity) or Factions::isSpawnRegion($damager)) {
            return;
        } elseif(SOTW::isEnable()) {
            return;
        } elseif($entity->isInvincibility() or $damager->isInvincibility()) {
            return;
        }
        if($damager->hasSuperKatanaAbility()) {
            $entity->setHealth($entity->getHealth() - 1);
        }
    }

}