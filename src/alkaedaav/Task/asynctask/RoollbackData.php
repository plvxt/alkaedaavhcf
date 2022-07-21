<?php


namespace alkaedaav\Task\asynctask;

use alkaedaav\Loader;
use alkaedaav\player\{Player, PlayerBase};

use alkaedaav\item\Items;
use alkaedaav\Task\event\InvincibilityTask;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;

class RoollbackData extends AsyncTask {

    /** @var String */
    protected $name;

    /** @var Array[] */
    protected $items;

    /** @var Array[] */
    protected $armorItems;

    /** @var String */
    protected $config;

    /**
     * RoollbackData Constructor.
     * @param String $name
     * @param Array $items
     * @param Array $armorItems
     * @param Config $config
     */
    public function __construct(String $name, Array $items, Array $armorItems, Config $config){
        $this->name = $name;
        $this->items = $items;
        $this->armorItems = $armorItems;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function onRun() : void {
        $fileData = [];
        $file = $this->config;
        if($file->exists($this->name)){
            $file->remove($this->name);
        }
        foreach ($this->items as $slot => $item) {
            $fileData["items"][$slot] = Items::itemSerialize($item);
        }
        foreach ($this->armorItems as $slot => $item) {
            $fileData["armorItems"][$slot] = Items::itemSerialize($item);
        }
        $file->set($this->name, $fileData);
        $file->save();
    }

    /**
     * @param Server $server
     * @return void
     */
    public function onCompletion(Server $server) : void {
        $player = $server->getPlayer($this->name);
        if(empty($player)){
            return;
        }
        $player->setInvincibility(true);
        PlayerBase::setData($player->getName(), "pvp_time", (1 * 3600));
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new InvincibilityTask($player), 20);
    }
}

?>