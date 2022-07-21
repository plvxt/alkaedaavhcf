<?php

declare(strict_types=1);

namespace alkaedaav\listeners;

use alkaedaav\Factions;

use pocketmine\block\BlockFactory;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

/**
 * Class Subclaim
 * @package alkaedaav\listeners
 */
class Subclaim implements Listener
{
    
    /**
     * @param BlockBreakEvent $event
     */
    public function handleBreak(BlockBreakEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        
        $tile = $block->getLevelNonNull()->getTile($block);
        
        if ($event->isCancelled())
            return;
        
        if ($tile instanceof Chest) {
            $pair = $tile->getPair();
            $factionName = Factions::getRegionName($block);
            $subclaimOwner = Factions::getSubclaim($block->asPosition());
            
            if ($subclaimOwner !== null) {
                $factionPlayer = Factions::getFaction($player->getName());
                
                if ($factionName !== null && Factions::getStrength($factionName) > 0) {
                    if ($factionName === $factionPlayer) {
                        if ($subclaimOwner === $player->getName() || $player->isGodMode() === true || Factions::getLeader($factionPlayer) === $player->getName()) {
                            Factions::removeSubclaim($block->asPosition());

                            if ($pair !== null) {
                                $chest = $pair->getBlock();
                        
                                if (Factions::getSubclaim($chest->asPosition()))
                                    $player->getLevelNonNull()->useBreakOn($chest->asPosition(), $item, $player, true);
                            }
                        } else $event->setCancelled();
                    }
                }
            }
        } elseif ($tile instanceof Sign) {
            if (TextFormat::clean($tile->getLine(0)) === '[subclaim]')
                $event->setCancelled();
        }
    }
    
    /**
     * @param SignChangeEvent $event
     */
    public function handleChange(SignChangeEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        
        $signTile = $block->getLevelNonNull()->getTile($block);
        
        $sideBlock = $block->getLevelNonNull()->getBlock($block->getSide($block->getDamage(), -1));
        
        $factionName = Factions::getRegionName($block);
        $factionNameSideBlock = Factions::getRegionName($sideBlock);
        $factionPlayer = Factions::getFaction($player->getName());
        
        if ($factionName !== null && $factionNameSideBlock !== null && $factionPlayer !== null) {
            $dtr = Factions::getStrength($factionName);
            
            if ($dtr > 0 && $factionName === $factionNameSideBlock && $factionName === $factionPlayer) {
                $subclaimOwner = Factions::getSubclaim($sideBlock->asPosition());
                
                
                if ($subclaimOwner === null || $subclaimOwner === $player->getName() && $sideBlock->getId() === 54) {
                    if ($event->getLine(0) === '[subclaim]') {
                        $tile = $block->getLevelNonNull()->getTile($sideBlock);
                        
                        if ($tile instanceof Chest) {
                        
                            if (Factions::getSubclaim($sideBlock->asPosition()) === null)
                                Factions::addSubclaim($player->getName(), $sideBlock->asPosition());
                            
                            if (($pairChest = $tile->getPair()) !== null) {
                                if (Factions::getSubclaim($pairChest->getBlock()->asPosition()) === null)
                                    Factions::addSubclaim($player->getName(), $pairChest->getBlock()->asPosition());
                            }
                        
                            $event->setLine(0, TextFormat::YELLOW . '[subclaim]');
                            $event->setLine(1, TextFormat::WHITE . $player->getName());
                        }
                    }
                }
            }
        }
    }
    
    /**
     * @param PlayerInteractEvent $event
     */
    public function handleInteract(PlayerInteractEvent $event): void
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        
        $tile = $block->getLevelNonNull()->getTile($block);
        
        if ($event->isCancelled())
            return;
        $factionName = Factions::getRegionName($block);
        $factionPlayer = Factions::getFaction($player->getName());
        
        if ($block->getId() === 54 && $tile instanceof Chest) {
            $pair = $tile->getPair();
            $subclaimOwner = Factions::getSubclaim($block->asPosition());
            
            if ($subclaimOwner !== null) {
                if ($factionName === null || Factions::getStrength($factionName) <= 0) {
                    // TODO: Hack!
                } else {
                    if ($factionName === $factionPlayer) {
                        if ($subclaimOwner !== $player->getName()) {
                            if (!$player->isGodMode() && Factions::getLeader($factionPlayer) !== $player->getName())
                                $event->setCancelled();
                        }
                    }
                }
            }
        }
    }
}