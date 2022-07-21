<?php

declare(strict_types=1);


namespace alkaedaav\listeners;


use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\Server;
use alkaedaav\block\EndPortalFrame;
use alkaedaav\Loader;
use alkaedaav\player\Player;

class EndListener implements Listener {

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $vector = $player->asVector3();
        $level = $player->getLevel();
        $level_name = $level->getName();
        $check_block = $this->checkBlock($level->getBlockAt($vector->getFloorx(), $vector->getFloorY(), $vector->getFloorZ()));

        $server = Server::getInstance();
        $end_level = $server->getLevelByName(Loader::getDefaultConfig("LevelManager")["levelEndName"]);
        if($end_level === null) {
            return;
        }
        $default_level = $server->getDefaultLevel();
        if($level_name === $end_level->getName() and $check_block) {
            $player->teleport($default_level->getSafeSpawn());
        } elseif($level_name === $default_level->getName() and $check_block) {
            $player->teleport($end_level->getSafeSpawn());

        }
    }
    
    public function onBreak(BlockBreakEvent $event): void {
        if($event->getPlayer()->getLevel()->getName() === Loader::getDefaultConfig("LevelManager")["levelEndName"]) {
            $event->setCancelled();
        }
    }
    
    public function onPlace(BlockPlaceEvent $event): void {
        if($event->getPlayer()->getLevel()->getName() === Loader::getDefaultConfig("LevelManager")["levelEndName"]) {
            $event->setCancelled();
        }
    }

    public function onRespawn(PlayerRespawnEvent $event): void {
        if($event->getPlayer()->getLevel()->getName() === Loader::getDefaultConfig("LevelManager")["levelEndName"]) {
            $event->setRespawnPosition(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
        }
    }

    private function checkBlock(Block $block): bool {
        if($block instanceof EndPortalFrame or $block->getId() === 119) {
            return true;
        }
        return false;
    }

}