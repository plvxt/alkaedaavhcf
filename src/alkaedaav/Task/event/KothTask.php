<?php

namespace alkaedaav\Task\event;

use alkaedaav\Loader;
use alkaedaav\Factions;
use alkaedaav\player\Player;

use alkaedaav\crate\CrateManager;

use alkaedaav\Task\asynctask\DiscordMessage;

use alkaedaav\koth\{Koth, KothManager};

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TE;

class KothTask extends Task {

    /** @var String */
    protected $kothName;

    /** @var Int */
    protected $kothTime;

    /**
     * KothTask Constructor.
     * @param String $kothName
     * @param Int $kothTime
     */
    public function __construct(String $kothName, Int $kothTime = null){
        $this->kothName = $kothName;
        $this->kothTime = $kothTime;

        Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{kothName}"], ["§", $kothName], Loader::getConfiguration("messages")->get("koth_was_started")));
    }

    /**
     * @param Int $currentTick
     * @return void
     */
    public function onRun(Int $currentTick) : void {
        $koth = KothManager::getKoth($this->kothName);
        if(empty($koth)){
        	Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        $koth->setEnable(true);
        if(!$koth->isEnable()){
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        if($koth->getCapturer() === null||!$koth->getCapturer()->isOnline()||(!$koth->isInPosition($koth->getCapturer()))){
            $koth->setCapture(false);
            $koth->setKothTime(!empty($this->kothTime) ? $this->kothTime : $koth->getDefaultKothTime());
            $koth->setCapturer(null);
            foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                if($koth->isInPosition($player) && !$player->isInvincibility()){
                    if(empty($koth->getCapturer())) $koth->setCapturer($player);
                }
            }
            if(!empty($koth->getCapturer())){

                Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{kothName}", "{playerName}"], ["§", $koth->getName(), $koth->getCapturer()->getName()], Loader::getConfiguration("messages")->get("koth_is_capturing")));
            }
        }
        if($koth->getKothTime() === 0){
            if(empty($koth->getCapturer())) return;
            
            $player = $koth->getCapturer();
            
            CrateManager::giveKey($koth->getCapturer(), "Koth", rand(1, 4));

            Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{kothName}", "{playerName}"], ["§", $koth->getName(), $koth->getCapturer()->getName()], Loader::getConfiguration("messages")->get("koth_is_captured")));
            
            # Da los puntos al jugador que capturó el koth
            Factions::addPoints(Factions::getFaction($koth->getCapturer()->getName()), 5);
			
            # Webhook to send the message to discord
            $message = $koth->getName()." KOTH was captured by ".$koth->getCapturer()->getName();
            Loader::getInstance()->getServer()->getAsyncPool()->submitTask(new DiscordMessage(Loader::getDefaultConfig("URL"), $message, "HCF | Koth Information"));        
            $koth->setEnable(false);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }else{
            $koth->setKothTime($koth->getKothTime() - 1);
        }
    }
}

?>