<?php

namespace alkaedaav;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

use alkaedaav\provider\{
    SQLite3Provider, YamlProvider, MysqlProvider,
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
        MysqlProvider::connect();
        SQLite3Provider::connect();
        $msg = <<<TAG
		 _ _                  _                   
        | | |                | |                  
    __ _| | | ____ _  ___  __| | __ _  __ ___   __
   / _` | | |/ / _` |/ _ \/ _` |/ _` |/ _` \ \ / /
  | (_| | |   < (_| |  __/ (_| | (_| | (_| |\ V / 
   \__,_|_|_|\_\__,_|\___|\__,_|\__,_|\__,_| \_/  

               Plugin by alkaedaav
             Discord: alkaedaav#9877
            Edited for: Blaze Network     
                                                                
TAG;
        $this->getLogger()->info($msg);
        Listeners::init();
        Commands::init();
        Items::init();
        Blocks::init();
        Entitys::init();
        Enchantments::init();
        
        YamlProvider::init();
        
        Factions::init();
        if (!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);

        $this->getScheduler()->scheduleRepeatingTask(new BardTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new ArcherTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new MageTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new NinjaClassTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new FactionTask(), 5 * 60 * 40);
        new TagPlayer();

        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "mw load " . $this->getConfig()->get("LevelManager")["levelEndName"]);
    }
    
    /**
     * @return void
     */
    public function onDisable() : void {
        SQLite3Provider::disconnect();
        MysqlProvider::disconnect();

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