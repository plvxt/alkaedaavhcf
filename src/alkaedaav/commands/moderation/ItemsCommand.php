<?php

namespace alkaedaav\commands\moderation;

use alkaedaav\Loader;

use alkaedaav\item\specials\{AntiTrapper,
    LoggerBait,
    NinjaShear,
    StormBreaker,
    EggPorts,
    Strength,
    Resistance,
    Invisibility,
    PotionCounter,
    Firework,
    CloseCall,
    RemovePearl,
    Cactus,
    RageMode,
    RageBrick,
    TankMode,
    MediKit,
    ZombieBardItem,
    Sky,
    GraplingHook,
    HolyClocks};

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class ItemsCommand extends PluginCommand {
	
	/**
	 * SpecialItemsCommand Constructor.
	 */
	public function __construct(){
        parent::__construct("items", Loader::getInstance());
        parent::setDescription("Get all special items from the server");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, array $args) : void {
        if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
        }
        $stormbreaker = new StormBreaker();
        $antitrapper = new AntiTrapper();
		$eggports = new EggPorts();
		$strength = new Strength();
		$resistance = new Resistance();
		$invisibility = new Invisibility();
		$potionCounter = new PotionCounter();
		$removePearl = new RemovePearl();
		$cactus = new Cactus();
		$closeCall = new CloseCall();
		$rageMode = new RageMode();
		$rageBrick = new RageBrick();
		$tankMode = new TankMode();
		$mediKit = new MediKit();
		$bard = new ZombieBardItem();
		$sky = new Sky();
		$gra = new GraplingHook();
		$holy = new HolyClocks();
		$firework = new Firework();
		$loggerbait = new LoggerBait();
		$ninjashear = new NinjaShear();
		

        $sender->getInventory()->addItem($stormbreaker);
        $sender->getInventory()->addItem($antitrapper);
		$sender->getInventory()->addItem($eggports);
		$sender->getInventory()->addItem($strength);
		$sender->getInventory()->addItem($resistance);
		$sender->getInventory()->addItem($invisibility);
        $sender->getInventory()->addItem($potionCounter);
        $sender->getInventory()->addItem($cactus);
        $sender->getInventory()->addItem($removePearl);
        $sender->getInventory()->addItem($closeCall);
        $sender->getInventory()->addItem($rageMode);
        $sender->getInventory()->addItem($rageBrick);
        $sender->getInventory()->addItem($tankMode);
        $sender->getInventory()->addItem($tankMode);
        $sender->getInventory()->addItem($mediKit);
        $sender->getInventory()->addItem($bard);
        $sender->getInventory()->addItem($sky);
        $sender->getInventory()->addItem($gra);
        $sender->getInventory()->addItem($holy);
        $sender->getInventory()->addItem($firework);
        $sender->getInventory()->addItem($loggerbait);
        $sender->getInventory()->addItem($ninjashear);
        
        
	}
}

?>