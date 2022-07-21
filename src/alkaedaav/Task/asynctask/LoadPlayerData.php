<?php

namespace alkaedaav\Task\asynctask;

use alkaedaav\Loader;
use alkaedaav\player\Player;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

use mysqli_connect;
use mysqli_fetch_assoc;

class LoadPlayerData extends AsyncTask {

    /** @var String */
    protected $name;

    /** @var String */
    protected $rank = null;

    /** @var String */
    protected $prefix = null;

    /** @var String */
    protected $hostname;

    /** @var String */
    protected $username;

    /** @var String */
    protected $password;

    /** @var String */
    protected $database;

    /** @var Int */
    protected $port;

    /**
     * LoadPlayerData Constructor.
     * @param String $name
     * @param String $uuid
     * @param String $hostname
     * @param String $username
     * @param String $password
     * @param String $database
     * @param Int $port
     */
    public function __construct(String $name, String $uuid, String $hostname, String $username, String $password, String $database, Int $port){
        $this->name = $name;
        $this->uuid = $uuid;

        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }

    /**
     * @return void
     */
    public function onRun() : void {
        $connection = mysqli_connect($this->hostname, $this->username, $this->password, $this->database, $this->port);

        $result = mysqli_fetch_assoc($connection->query("SELECT * FROM players_data_ranks WHERE player_name = '$this->name';"));
        
        if(!empty($result["rank_id"])) $this->rank = $result["rank_id"];

        $connection->close();
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
        if($player instanceof Player){
            $player->setRank($this->rank);
            $player->showCoordinates();
            $player->addPermissionsPlayer();
            
        }
    }
}

?>