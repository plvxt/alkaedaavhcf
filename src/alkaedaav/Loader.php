<?php

namespace alkaedaav;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

use alkaedaav\provider\{
    SQLite3Provider, YamlProvider,
};
use alkaedaav\player\{
    Player,
};
use alkaedaav\API\{
    Scoreboards,
};
use alkaedaav\Task\{BardTask, ArcherTask, specials\NinjaClassTask, TagPlayer, MageTask};
use alkaedaav\Task\event\{
	FactionTask,
};
use alkaedaav\listeners\{
	Listeners,
};
use alkaedaav\commands\{
    Commands,
};
use alkaedaav\item\{
    Items,
};
use alkaedaav\block\{
    Blocks,
};
use alkaedaav\entities\{
    Entitys,
};
use alkaedaav\enchantments\{
    Enchantments,
};
use libs\muqsit\invmenu\InvMenuHandler;

class Loader extends PluginBase {
    
    /** @var Loader */
    protected static $instance;
    
    /** @var array[] */
    public static $appleenchanted = [], $rogue = [];
    
    /** @var array[] */
	public $permission = [];
    
    /**
     * @return void
     */
    public function onLoad() : void {
        self::$instance = $this;
    }
    
    /**
     * @return void
     */
    public function onEnable() : void {
                $msg = <<<TAG
        
       

░██████╗░█████╗░███╗░░██╗██████╗░██╗░█████╗░██╗░░░██╗██╗██████╗░
██╔════╝██╔══██╗████╗░██║██╔══██╗██║██╔══██╗██║░░░██║██║██╔══██╗
╚█████╗░███████║██╔██╗██║██║░░██║██║███████║╚██╗░██╔╝██║██████╔╝
░╚═══██╗██╔══██║██║╚████║██║░░██║██║██╔══██║░╚████╔╝░██║██╔═══╝░
██████╔╝██║░░██║██║░╚███║██████╔╝██║██║░░██║░░╚██╔╝░░██║██║░░░░░
╚═════╝░╚═╝░░╚═╝╚═╝░░╚══╝╚═════╝░╚═╝╚═╝░░╚═╝░░░╚═╝░░░╚═╝╚═╝░░░░░

               Plugin by nmoralesFZ
             Discord: nmoralesFZ#2607
 Version: 3.0.1 - Github: nmoralesFZ/alkaedaavhcf
                                                                
TAG;
        $this->getLogger()->info($msg);
        SQLite3Provider::connect();
        $this->getServer()->getLogger()->notice("SQLite3 is working!");
        Listeners::init();
        $this->getServer()->getLogger()->notice("Loaded plugin files!");
        Commands::init();
        $this->getServer()->getLogger()->notice("Loaded command files!");
        Items::init();
        $this->getServer()->getLogger()->notice("Loaded items files!");
        Blocks::init();
        $this->getServer()->getLogger()->notice("Loaded blocks files!");
        Entitys::init();
        $this->getServer()->getLogger()->notice("Loaded entitys files!");
        Enchantments::init();
        $this->getServer()->getLogger()->notice("Loaded enchantments files!");
        
        YamlProvider::init();
        $this->getServer()->getLogger()->notice("Created config files!, check plugin_data folder");
        
        Factions::init();
        $this->getServer()->getLogger()->notice("Factions loaded!");
        if (!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);

        $this->getScheduler()->scheduleRepeatingTask(new BardTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new ArcherTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new MageTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new NinjaClassTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new FactionTask(), 5 * 60 * 40);
        new TagPlayer();

        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw load netherav");        
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw load endav");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw load buildsav");
    }
    
    /**
     * @return void
     */
    public function onDisable() : void {
        SQLite3Provider::disconnect();

        YamlProvider::save();
    }

    /**
     * @return Loader
     */
    public static function getInstance() : Loader {
        return self::$instance;
    }

    /**
     * @return SQLite3Provider
     */
    public static function getProvider() : SQLite3Provider {
        return new SQLite3Provider();
    }

    /**
     * @return Scoreboards
     */
	public static function getScoreboard() : Scoreboards {
		return new Scoreboards();
    }

    /**
     * @param String $configuration
     */
    public static function getDefaultConfig($configuration){
        return self::getInstance()->getConfig()->get($configuration);
    }
    
    /**
     * @param String $configuration
     */
    public static function getConfiguration($configuration){
    	return new Config(self::getInstance()->getDataFolder()."{$configuration}.yml", Config::YAML);
    }

    /**
     * @param Player $player
     */
    public function getPermission(Player $player){
        if(!isset($this->permission[$player->getName()])){
            $this->permission[$player->getName()] = $player->addAttachment($this);
        }
        return $this->permission[$player->getName()];
    }
}

?>
