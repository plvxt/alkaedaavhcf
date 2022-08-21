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
        $event->setJoinMessage(TE::GRAY."[".TE::GREEN."+".TE::GRAY."] ".TE::GRAY.$player->getName().TE::GOLD." joined");
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
		$event->setQuitMessage(TE::GRAY."[".TE::RED."-".TE::GRAY."] ".TE::GRAY.$player->getName().TE::GOLD." left");

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
    		$format = TE::GOLD."[".TE::GREEN."Guest".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Guest_COL"){
    		$format = TE::GOLD."[".TE::GREEN."Guest".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Guest_MEX"){
    		$format = TE::GOLD."[".TE::GREEN."Guest".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Guest_Demon"){
    		$format = TE::GOLD."[".TE::GREEN."Guest".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Guest_Lmao"){
    		$format = TE::GOLD."[".TE::GREEN."Guest".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}

		
    	if($this->getRank($player) === "Owner"){
    		$format = TE::GOLD."[".TE::DARK_RED."Owner".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Owner_COL"){
    		$format = TE::GOLD."[".TE::DARK_RED."Owner".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Owner_MEX"){
    		$format = TE::GOLD."[".TE::DARK_RED."Owner".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Owner_Demon"){
    		$format = TE::GOLD."[".TE::DARK_RED."Owner".TE::GOLD."] ".TE::GOLD."[".TE::RED.TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Owner_Lmao"){
    		$format = TE::GOLD."[".TE::DARK_RED."Owner".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
    	if($this->getRank($player) === null||$this->getRank($player) === "Owner_GND"){
    		$format = TE::GOLD."[".TE::DARK_RED."Owner".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::DARK_PURPLE."GND".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Co-Owner"){
    		$format = TE::GOLD."[".TE::RED."Co-Owner".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Co-Owner_COL"){
    		$format = TE::GOLD."[".TE::RED."Co-Owner".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Co-Owner_MEX"){
    		$format = TE::GOLD."[".TE::RED."Co-Owner".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Co-Owner_Demon"){
    		$format = TE::GOLD."[".TE::RED."Co-Owner".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Co-Owner_Lmao"){
    		$format = TE::GOLD."[".TE::RED."Co-Owner".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Admin"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Admin".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Admin_COL"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Admin".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Admin_MEX"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Admin".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Admin_Demon"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Admin".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Admin_Lmao"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Admin".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."♡".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Sr-Mod"){
    		$format = TE::GOLD."[".TE::DARK_PURPLE."Sr-Mod".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Mod_COL"){
    		$format = TE::GOLD."[".TE::DARK_PURPLE."Sr-Mod".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Mod_MEX"){
    		$format = TE::GOLD."[".TE::DARK_PURPLE."Sr-Mod".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Mod_Demon"){
    		$format = TE::GOLD."[".TE::DARK_PURPLE."Sr-Mod".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Mod_Lmao"){
    		$format = TE::GOLD."[".TE::DARK_PURPLE."Sr-Mod".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
    	if($this->getRank($player) === "Mod"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Mod+".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mod_COL"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Mod".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mod_MEX"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Mod".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mod_Demon"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Mod".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
            }
            if($this->getRank($player) === null||$this->getRank($player) === "Sr.Admin"){
    		$format = TE::GOLD."[".TE::GREEN."Sr Admin".TE::GOLD."] ".TE::GOLD."[".TE::RED."".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mod_Lmao"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Mod".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Partner"){
    		$format = TE::GOLD."[".TE::DARK_PURPLE."Partner".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Partner_COL"){
    		$format = TE::GOLD."[".TE::GREEN."Partner".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Partner_MEX"){
    		$format = TE::GOLD."[".TE::GREEN."Partner".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Partner_Demon"){
    		$format = TE::GOLD."[".TE::GREEN."Partner".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Partner_Lmao"){
    		$format = TE::GOLD."[".TE::GREEN."Partner".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
    	if($this->getRank($player) === null||$this->getRank($player) === "Partner_GND"){
    		$format = TE::GOLD."[".TE::YELLOW."Partner".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::DARK_PURPLE."GND".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
        if($this->getRank($player) === null||$this->getRank($player) === "Partner_<3"){
    		$format = TE::GOLD."[".TE::YELLOW."Partner".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::DARK_RED."<3".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
        

    	if($this->getRank($player) === "Sr-Admin"){
    		$format = TE::GOLD."[".TE::AQUA."Sr-Admin".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Admin_COL"){
    		$format = TE::GOLD."[".TE::AQUA."Sr-Admin".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Admin_MEX"){
    		$format = TE::GOLD."[".TE::AQUA."Sr-Admin".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Admin_Demon"){
    		$format = TE::GOLD."[".TE::AQUA."Sr-Admin".TE::GOLD."] ".TE::GOLD."[".TE::BLUE."C".TE::WHITE."H".TE::RED."I".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Sr-Admin_Lmao"){
    		$format = TE::GOLD."[".TE::AQUA."Sr-Admin".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Trainee"){
    		$format = TE::GOLD."[".TE::YELLOW."Trainee".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Trainee_COL"){
    		$format = TE::GOLD."[".TE::YELLOW."Trainee".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Trainee_MEX"){
    		$format = TE::GOLD."[".TE::YELLOW."Trainee".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Trainee_Demon"){
    		$format = TE::GOLD."[".TE::YELLOW."Trainee".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Trainee_Lmao"){
    		$format = TE::GOLD."[".TE::YELLOW."Trainee".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Developer"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Developer".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
    	
		if($this->getRank($player) === null||$this->getRank($player) === "Dev_Sup"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Developer".TE::GOLD."] ".TE::GOLD."[".TE::RED."Supr".TE::WHITE."eme".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Developer_COL"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Developer".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
        if($this->getRank($player) === "Mercury_Christmas") {
    $format = TE::GOLD . "[§1Mercury" . TE::GOLD . "] [§l§fChristmas" . TE::GOLD . "] " . $player->getName()  . TE::WHITE;
}
		if($this->getRank($player) === null||$this->getRank($player) === "Developer_MEX"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Developer".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Developer_Demon"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Developer".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Developer_Lmao"){
    		$format = TE::GOLD."[".TE::DARK_AQUA."Developer".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Venus"){
    		$format = TE::GOLD."[".TE::YELLOW . "Venus".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Venus_COL"){
    		$format = TE::GOLD."[".TE::YELLOW."Venus".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Venus_MEX"){
    		$format = TE::GOLD."[".TE::YELLOW."Venus".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Venus_Demon"){
    		$format = TE::GOLD."[".TE::YELLOW."Venus".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Venus_Lmao"){
    		$format = TE::GOLD."[".TE::YELLOW."Venus".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Mercury"){
    		$format = TE::GOLD."[". TE::DARK_BLUE . "Mercury" .TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mercury_ESP"){
    		$format = TE::GOLD."[".TE::DARK_BLUE."Mercury".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."E".TE::GOLD."S".TE::RED."P".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mercury_MEX"){
    		$format = TE::GOLD."[".TE::DARK_BLUE."Mercury".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mercury_Demon"){
    		$format = TE::GOLD."[".TE::DARK_BLUE."Mercury".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Mercury_Lmao"){
    		$format = TE::GOLD."[".TE::DARK_BLUE."Mercury".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Moon"){
    	  $format = TE::GOLD ."[" . TE::WHITE . "Moon" . TE::GOLD . "] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Moon_COL"){
    		$format = TE::GOLD."[".TE::WHITE."Moon".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Moon_MEX"){
    		$format = TE::GOLD."[".TE::WHITE."".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Moon_Demon"){
    		$format = TE::GOLD."[".TE::WHITE."Moon".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Moon_Lmao"){
    		$format = TE::GOLD."[".TE::WHITE."Moon".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."♡".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Saturn"){
    	  $format = TE::GOLD ."[" . TE::RED . "Saturn" . TE::GOLD . "] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Saturn_COL"){
    		$format = TE::GOLD."[".TE::YELLOW."Saurn".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Saturn_MEX"){
    		$format = TE::GOLD."[".TE::YELLOW."Saurn".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Saturn_Demon"){
    		$format = TE::GOLD."[".TE::YELLOW."Saurn".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Saturn_Lmao"){
    		$format = TE::GOLD."[".TE::YELLOW."Saurn".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Booster"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Booster".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster_COL"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."NitroBooster".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster_MEX"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."NitroBooster".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster_Demon"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Booster".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster_Lmao"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."NitroBooster".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."♡".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Booster-Cloned"){
    		$format = TE::GOLD."[".TE::DARK_GREEN."Booster Cloned".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster Cloned_COL"){
    		$format = TE::GOLD."[".TE::DARK_GREEN."Booster Cloned".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster Cloned_MEX"){
    		$format = TE::GOLD."[".TE::DARK_GREEN."Booster Cloned".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Booster Cloned_CORA"){
    		$format = TE::GOLD."[".TE::DARK_GREEN."Booster Cloned".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."♡".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Blaze"){
    		$format = TE::GOLD."[".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::AQUA."Blaze".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Blaze_COL"){
    		$format = TE::GOLD."[".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::AQUA."Blaze".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Blaze_MEX"){
    		$format = TE::GOLD."[".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::AQUA."Blaze".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Blaze_Demon"){
    		$format = TE::GOLD."[".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::AQUA."Blaze".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Blaze_Lmao"){
    		$format = TE::GOLD."[".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::AQUA."Blaze".TE::OBFUSCATED.TE::YELLOW."!!".TE::RESET.TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "MiniYT"){
    		$format = TE::GOLD."[".TE::WHITE."Mini".TE::RED."YT".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "MiniYTt_COL"){
    		$format = TE::GOLD."[".TE::WHITE."Mini".TE::RED."YT".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "MiniYT_MEX"){
    		$format = TE::GOLD."[".TE::WHITE."Mini".TE::RED."YT".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "MiniYT_Demon"){
    		$format = TE::GOLD."[".TE::WHITE."Mini".TE::RED."YT".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "MiniYT_Lmao"){
    		$format = TE::GOLD."[".TE::WHITE."Mini".TE::RED."YT".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Youtuber"){
    		$format = TE::GOLD."[".TE::WHITE."You".TE::RED."Tuber".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Youtuber_COL"){
    		$format = TE::GOLD."[".TE::WHITE."You".TE::RED."Tuber".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Youtuber_MEX"){
    		$format = TE::GOLD."[".TE::WHITE."You".TE::RED."Tuber".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Youtuber_Demon"){
    		$format = TE::GOLD."[".TE::WHITE."You".TE::RED."Tuber".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Youtuber_Lmao"){
    		$format = TE::GOLD."[".TE::WHITE."You".TE::RED."Tuber".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}


    	if($this->getRank($player) === "Famous"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Famous".TE::GOLD."] ".TE::LIGHT_PURPLE.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Famous_COL"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Famous".TE::GOLD."] ".TE::GOLD."[".TE::YELLOW."C".TE::BLUE."O".TE::RED."L".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Famous_MEX"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Famous".TE::GOLD."] ".TE::GOLD."[".TE::GREEN."M".TE::WHITE."E".TE::RED."X".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Famous_Demon"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Famous".TE::GOLD."] ".TE::GOLD."[".TE::RED."Demon".TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
		if($this->getRank($player) === null||$this->getRank($player) === "Famous_Lmao"){
    		$format = TE::GOLD."[".TE::LIGHT_PURPLE."Famous".TE::GOLD."] ".TE::GOLD."[".TE::BOLD.TE::RED."Lmao".TE::RESET.TE::GOLD."] ".TE::GOLD.$player->getName().TE::WHITE;
    	}
    	if(Factions::inFaction($player->getName())){
			$factionName = Factions::getFaction($player->getName());
			$event->setFormat(TE::GOLD."[".TE::RED.$factionName.TE::GOLD."]".TE::RESET.$format.": ".$event->getMessage(), true);
		}else{
			$event->setFormat($format.": ".$event->getMessage(), true);
		}
	}
    
    public function onInteract(PlayerInteractEvent $event): void {
        if($event->getItem()->getId() === ItemIds::FLINT_STEEL and $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            $event->setCancelled();
        }
    }
	
}

?>