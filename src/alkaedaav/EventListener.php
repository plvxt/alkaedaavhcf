<?php

namespace alkaedaav;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\{Player, PlayerBase};

use alkaedaav\Task\asynctask\{LoadPlayerData, SavePlayerData};

use alkaedaav\Task\Scoreboard;

use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TE;
use pocketmine\level\biome\Biome;
use pocketmine\item\ItemIds;

use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerChatEvent, PlayerMoveEvent, PlayerInteractEvent};
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;

use pocketmine\network\mcpe\protocol\LevelEventPacket;

class EventListener implements Listener {

    /**
     * EventListener Constructor.
     */
    public function __construct(){
		
    }
    
    /**
     * @param PlayerCreationEvent $event
     * @return void
     */
    public function onPlayerCreationEvent(PlayerCreationEvent $event) : void {
        $event->setPlayerClass(Player::class, true);
    }

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onPlayerJoinEvent(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $event->setJoinMessage(TE::GRAY."[".TE::GREEN."+".TE::GRAY."] ".TE::GRAY.$player->getName().TE::GOLD." ");
        if(!$player instanceof Player) {
            return;
        }
        PlayerBase::create($player->getName());
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new Scoreboard($player), 20);
    }

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
    public function onPlayerQuitEvent(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
		$event->setQuitMessage(TE::GRAY."[".TE::RED."-".TE::GRAY."] ".TE::GRAY.$player->getName().TE::GOLD." ");

        Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new SavePlayerData($player->getName(), $player->getUniqueId()->toString(), $player->getClientId(), $player->getAddress(), Factions::inFaction($player->getName()) ? Factions::getFaction($player->getName()) : "This player not have faction"));
        if($player instanceof Player){
            $player->removePermissionsPlayer();
		}
	}
	
/**
     * @param EntityLevelChangeEvent $event
     * @return void
     */
	public function onEntityLevelChangeEvent(EntityLevelChangeEvent $event) : void {
		$player = $event->getEntity();
		$player->showCoordinates();
	}
	public function getRank(Player $player): string {
		$PurePerms = Loader::getInstance()->getServer()->getPluginManager()->getPlugin("PurePerms");
			$message = $PurePerms->getUserDataMgr()->getGroup($player)->getName();
			if($message != null){
			  return $message;
			} else {
			  return "NoRank";
			}
		  
  }
    /**
     * @param PlayerChatEvent $event
     * @return void
     */
     
    public function onPlayerChatEvent(PlayerChatEvent $event) : void {
    	$player = $event->getPlayer();
    	$format = null;
    	if(!$player instanceof Player) {
    	    return;
        }
    	if($this->getRank($player) === null||$this->getRank($player) === "Guest"){
    		$format = "§9User §8| §7".$player->getName().TE::WHITE;
    	}
		
    	if($this->getRank($player) === "Owner"){
    		$format = "§4Owner §8| §7".$player->getName().TE::WHITE;
    	}

    	if($this->getRank($player) === "Admin"){
    		$format = "§cAdmin §8| §7".$player->getName().TE::WHITE;
    	}

    	if($this->getRank($player) === "Ultra"){
    		$format = "§5Ultra §8| §7".$player->getName().TE::WHITE;
    	}
        
    	if($this->getRank($player) === "Trainee"){
    		$format = "§3Trainee §8| §7".$player->getName().TE::WHITE;
    	}
        
    	if($this->getRank($player) === "Staff"){
    		$format = "§2Staff §8| §7".$player->getName().TE::WHITE;
    	}
        
    	if($this->getRank($player) === "Partner"){
    		$format = "§aPartner §8| §7".$player->getName().TE::WHITE;
    	}
        
    	if($this->getRank($player) === "Media"){
    		$format = "§gMedia §8| §7".$player->getName().TE::WHITE;
    	}
        
    	if($this->getRank($player) === "Booster"){
    		$format = "§dBooster §8| §7".$player->getName().TE::WHITE;
    	}
        
    	if($this->getRank($player) === "Developer"){
    		$format = "§bDeveloper §8| §7".$player->getName().TE::WHITE;
    	}
    	if($this->getRank($player) === "VIP"){
    		$format = "§eVIP §8| §7".$player->getName().TE::WHITE;
    	}
    	if($this->getRank($player) === "Princess"){
    		$format = "§r§7§kili §r§ePrincess §r§7§kili §r§8| §7".$player->getName().TE::WHITE;
    	}
        
    	if(Factions::inFaction($player->getName())){
			$factionName = Factions::getFaction($player->getName());
			$event->setFormat("§8> §6".$factionName." §8| ".TE::RESET.$format."§7: §f".$event->getMessage(), true);
		}else{
			$event->setFormat("§8> §6".$format."§7: §f".$event->getMessage(), true);
		}
	}
    
    public function onInteract(PlayerInteractEvent $event): void {
        if($event->getItem()->getId() === ItemIds::FLINT_STEEL and $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $event->setCancelled();
        }
    }
	
}

?>