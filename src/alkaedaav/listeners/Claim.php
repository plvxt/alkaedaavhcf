<?php

namespace alkaedaav\listeners;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use alkaedaav\API\System;
use alkaedaav\utils\Tower;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TE;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

use pocketmine\event\player\{PlayerChatEvent, PlayerInteractEvent, PlayerDropItemEvent};
use pocketmine\event\block\BlockBreakEvent;

class Claim implements Listener {

    /**
     * Claim Constructor.
     */
    public function __construct(){
        
    }

    /**
     * @param PlayerDropItemEvent $event
     * @return void
     */
    public function onPlayerDropItemEvent(PlayerDropItemEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isInteract() && $player->getInventory()->getItemInHand()->getCustomName() === TE::DARK_PURPLE."Claim Tool"){
            $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_drop_tool")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $spawn = new Vector3(0, 0, 0);
        if($player->isInteract() && $player->getInventory()->getItemInHand()->getCustomName() === TE::DARK_PURPLE."Claim Tool" && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            $event->setCancelled(true);
            if((int)$spawn->distance($block) < 400 && !$player->isGodMode()){
                $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_small_distance_of_spawn")));
                $event->setCancelled(true);
                return;
            }
            if(!System::isPosition($player, 1)){
                $player->sendMessage(str_replace(["&", "{positionX}", "{positionZ}"], ["§", $block->x, $block->z], Loader::getConfiguration("messages")->get("faction_location_zone_first")));
                Tower::delete($player, 1);
                Tower::create($block, $player, Block::get(Block::GLASS));
                System::setPosition($player, $block, 1);
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreakEvent(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $spawn = new Vector3(0, 0, 0);
        if($player->isInteract() && $player->getInventory()->getItemInHand()->getCustomName() === TE::DARK_PURPLE."Claim Tool"){
            $event->setCancelled(true);
            if((int)$spawn->distance($block) < 400 && !$player->isGodMode()){
                $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_small_distance_of_spawn")));
                $event->setCancelled(true);
                return;
            }
            if(!System::isPosition($player, 1)) return;

            $player->sendMessage(str_replace(["&", "{positionX}", "{positionZ}"], ["§", $block->x, $block->z], Loader::getConfiguration("messages")->get("faction_location_zone_second")));
            Tower::delete($player, 2);
            System::deletePosition($player, 2);
            Tower::create($block, $player, Block::get(Block::GLASS));
            System::setPosition($player, $block, 2);
            System::checkClaim($player, System::getPosition($player, 1), System::getPosition($player, 2));
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onPlayerChatEvent(PlayerChatEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isInteract() && $event->getMessage() === "accept"){
            if(Factions::getBalance(Factions::getFaction($player->getName())) < $player->getClaimCost()){
                $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_not_have_money_for_claim_zone")));
                $event->setCancelled(true);
                return;
            }
            if(!System::isPosition($player, 1) && !System::isPosition($player, 2)){
                $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_not_select_position")));
                return;
            }
            Factions::claimRegion(Factions::getFaction($player->getName()), $player->getLevel()->getName(), System::getPosition($player, 1), System::getPosition($player, 2), Factions::FACTION);
            Factions::reduceBalance(Factions::getFaction($player->getName()), $player->getClaimCost());

            Tower::delete($player, 1);
            Tower::delete($player, 2);
            System::deletePosition($player, 1, true);
            System::deletePosition($player, 2, true);
            $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_accept")));
            $event->setCancelled(true);
        }
        if($player->isInteract() && $event->getMessage() === "cancel"){
            Tower::delete($player, 1);
            Tower::delete($player, 2);
            System::deletePosition($player, 1, true);
            System::deletePosition($player, 2, true);
            $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_cancel")));
            $event->setCancelled(true);
        }
    }
}

?>