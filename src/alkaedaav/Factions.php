<?php

namespace alkaedaav;

use alkaedaav\Loader;
use alkaedaav\player\{Player, PlayerBase};

use alkaedaav\provider\SQLite3Provider;

use alkaedaav\Task\FreezeTimeTask;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\level\{Level, Position};

use pocketmine\block\Block;
use pocketmine\math\Vector3;

use pocketmine\Server;

use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class Factions {

    const FACTION = "Faction", SPAWN = "Spawn", PROTECTION = "Protection", KOTH = "Koth";

    /**
     * @return void
     */
    public static function init() : void {
    	if(empty(self::getFactions())){
			return;
		}
        foreach(self::getFactions() as $factionName){
            if(self::getStrength($factionName) < self::getMaxStrength($factionName)){
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new FreezeTimeTask($factionName, self::getFreezeTime($factionName)), 20);
            }
        }
    }

    /**
     * @param String $playerName
     * @return bool
     */
    public static function inFaction(String $playerName) : bool {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE playerName = '$playerName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(!empty($result)){
        	$data->finalize();
            return true;
        }else{
        	$data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }

    /**
     * @param String $playerName
     * @return String|null
     */
    public static function getFaction(String $playerName) : ?String {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE playerName = '$playerName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
			return null;
		}
    	return $result["factionName"];
    }

    /**
     * @param String $playerName
     * @param String $factionName
     * @param String $factionRank
     * @return void
     */
    public static function joinToFaction(String $playerName, String $factionName, String $factionRank = Player::LEADER) : void {
        $data = SQLite3Provider::getDataBase()->prepare("INSERT OR REPLACE INTO player_data(playerName, factionRank, factionName) VALUES (:playerName, :factionRank, :factionName);");
    	$data->bindValue(":playerName", $playerName);
    	$data->bindValue(":factionRank", $factionRank);
    	$data->bindValue(":factionName", $factionName);
        $data->execute();
        foreach(self::getPlayers($factionName) as $player){
            $online = Loader::getInstance()->getServer()->getPlayer($player);
            if($online instanceof Player){
                $online->sendMessage(str_replace(["&", "{playerName}"], ["§", $playerName], Loader::getConfiguration("messages")->get("faction_player_join_to_faction_correctly")));
            }
        }
    }

    /**
     * @param String $playerName
     * @return void
     */
    public static function removeToFaction(String $playerName) : void {
    	foreach(self::getPlayers(self::getFaction($playerName)) as $player){
            $online = Loader::getInstance()->getServer()->getPlayer($player);
            if($online instanceof Player){
                $online->sendMessage(str_replace(["&", "{playerName}"], ["§", $playerName], Loader::getConfiguration("messages")->get("faction_player_leave_to_faction_correctly")));
            }
        }
        SQLite3Provider::getDataBase()->query("DELETE FROM player_data WHERE playerName = '$playerName';");
    }

    /**
     * @param String $factionName
     * @param Player $player
     * @return void
     */
    public static function create(String $factionName, Player $player) : void {
        if(self::isFactionExists($factionName)||self::isRegionExists($factionName)){
            $player->sendMessage(str_replace(["&", "{factionName}"], ["§", $factionName], Loader::getConfiguration("messages")->get("faction_exists")));
            return;
        }
        $player->sendMessage(str_replace(["&", "{factionName}"], ["§", $factionName], Loader::getConfiguration("messages")->get("faction_create")));
        self::joinToFaction($player->getName(), $factionName, Player::LEADER);
        self::setStrength($factionName, 2);
        Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{factionName}", "{playerName}"], ["§", $factionName, $player->getNameTag()], Loader::getConfiguration("messages")->get("faction_create_correctly")));
    }

    /**
     * @param String $factionName
     * @return void
     */
    public static function remove(String $factionName) : void {
        foreach(self::getPlayers($factionName) as $playerName){
            self::removeToFaction($playerName);
        }
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        
        if (!empty($result)) {
            for ($x = $result['x1']; $x < $result['x2']; $x++) {
                for ($y = 0; $y <= 256; $y++) {
                    for ($z = $result['z1']; $z < $result['z2']; $z++) {
                        if (self::getSubclaim(new \pocketmine\level\Position($x, $y, $z, Server::getInstance()->getLevelByName($result['level']))))
                            self::removeSubclaim(new \pocketmine\level\Position($x, $y, $z, Server::getInstance()->getLevelByName($result['level'])));
                    }
                }
            }
        }
        
        SQLite3Provider::getDataBase()->exec("DELETE FROM player_data WHERE factionName = '$factionName';");
		SQLite3Provider::getDataBase()->exec("DELETE FROM strength WHERE factionName = '$factionName';");
		SQLite3Provider::getDataBase()->exec("DELETE FROM zoneclaims WHERE factionName = '$factionName' AND protection = 'Faction';");
		SQLite3Provider::getDataBase()->exec("DELETE FROM homes WHERE factionName = '$factionName';");
		SQLite3Provider::getDataBase()->exec("DELETE FROM balance WHERE factionName = '$factionName';");
        Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{factionName}"], ["§", $factionName], Loader::getConfiguration("messages")->get("faction_delete_correctly")));
    }

    /**
     * @return Array[]
     */
    public static function getFactions() : Array {
        $factions = [];
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data;");
        while($result = $data->fetchArray(SQLITE3_ASSOC)){
            $factions[] = $result["factionName"];
        }
        return $factions;
    }

    /**
     * @param String $factionName
     * @return bool 
     */
    public static function isFactionExists(String $factionName) : bool {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(!empty($result)){
        	$data->finalize();
            return true;
        }else{
        	$data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }
    
    /**
	 * @param String $factionName
	 * @return String|null
	 */
    public static function getListPlayers(String $factionName) : ?String {
    	$players = [];
    	$data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE factionName = '$factionName';");
    	while($result = $data->fetchArray(SQLITE3_ASSOC)){
    	    $player = Loader::getInstance()->getServer()->getPlayer($result["playerName"]);
            if($player instanceof Player){
                $players[] = TE::GREEN.$player->getName().TE::YELLOW."[".TE::GREEN.PlayerBase::getKills($result["playerName"]).TE::YELLOW."]";
            }else{
                $players[] = TE::GRAY.$result["playerName"].TE::YELLOW."[".TE::GREEN.PlayerBase::getKills($result["playerName"]).TE::YELLOW."]";
            }
        }
    	return empty($players) ? "No members connected" : implode(", ", $players);
    }
    
    /**
	 * @param String $factionName
	 * @return array|null
	 */
    public static function getPlayers(String $factionName) : ?array {
    	$players = [];
    	$data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE factionName = '$factionName';");
    	while($result = $data->fetchArray(SQLITE3_ASSOC)){        
            $players[] = $result["playerName"];
    	}
        return $players;
    }
    
    /**
     * @param String $factionName
     * @return Int|null
     */
    public static function getMaxPlayers(String $factionName) : ?Int {
    	$data = SQLite3Provider::getDataBase()->query("SELECT COUNT(playerName) as maxplayers FROM player_data WHERE factionName = '$factionName';");
		$result = $data->fetchArray();
		if($result["maxplayers"] === 0){
			return null;
		}
		return $result["maxplayers"];
    }

    /**
     * @param String $factionName
     * @return Int
     */
    public static function getOnlinePlayers(String $factionName) : Int {
        $players = [];
    	$data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE factionName = '$factionName';");
    	while($result = $data->fetchArray(SQLITE3_ASSOC)){        
            if(($player = Loader::getInstance()->getServer()->getPlayerExact($result["playerName"])) instanceof Player){
                $players[] = $player;
            }
    	}
        return count($players) ?? 0;
    }
    
    /**
     * @param String $factionName
     * @return String|null
     */
    public static function getLeader(String $factionName) : ?String {
    	$data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE factionName = '$factionName' and factionRank = 'Leader';");
    	$result = $data->fetchArray(SQLITE3_ASSOC);
    	if(empty($result)){
			return null;
		}
    	return $result["playerName"];
    }
    
    /**
     * @param String $factionName
     * @return String|null
     */
    public static function getCoLeader(String $factionName) : ?String {
    	$data = SQLite3Provider::getDataBase()->query("SELECT * FROM player_data WHERE factionName = '$factionName' and factionRank = 'Co_Leader';");
    	$result = $data->fetchArray(SQLITE3_ASSOC);
    	if(empty($result)){
			return null;
		}
    	return $result["playerName"];
    }

    /**
     * @param String $factionName
     * @return Int
     */
    public static function getStrength(String $factionName) : Int {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM strength WHERE factionName = '$factionName';");
		$result = $data->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			return (int)0;
		}
		return (int)$result["dtr"];
    }

    /**
	 * @param String $factionName
     * @return void
	 */
	public static function reduceStrength(String $factionName) : void {
		if(self::getStrength($factionName) === 1){
			self::setStrength($factionName, 0);
		}else{
			self::setStrength($factionName, self::getStrength($factionName) - 1);
		}
    }
    
    /**
     * @param String $factionName
     * @return Int
     */
    public static function getMaxStrength(String $factionName) : Int {
        $players = self::getMaxPlayers($factionName);
        $max = 7;
        if($players === 1){
        	$max = 2;
        }
        if($players === 2){
        	$max = 3;
        }
        if($players === 3){
        	$max = 4;
        }
        if($players === 4){
        	$max = 5;
        }
        if($players === 5){
        	$max = 6;
        }
        if($players === 6){
        	$max = 7;
        }
        return $max;
    }

    /**
     * @param String $factionName
     * @param Int $strength
     */
    public static function setStrength(String $factionName, Int $strength){
        $data = SQLite3Provider::getDataBase()->prepare("INSERT OR REPLACE INTO strength(factionName, dtr) VALUES (:factionName, :dtr);");
		$data->bindValue(":factionName", $factionName);
		$data->bindValue(":dtr", $strength);
        $data->execute();
    }

    static public function backup(): void {
        $sqlite = SQLite3Provider::getDataBase();
        $player_data = $sqlite->query("SELECT * FROM player_data;");
        while($result = $player_data->fetchArray(SQLITE3_ASSOC)) {
            if(self::isFreezeTime($name = $result["factionName"])) {
                self::setStrength($name, self::getMaxStrength($name));
            }
        }
    }

    /**
     * @param String $factionName
     * @return Int
     */
    public static function getBalance(String $factionName) : Int {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM balance WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
			return 0;
		}
		return $result["money"];
    }

    /**
     * @param String $factionName
     * @param Int $balance
     * @return void
     */
    public static function addBalance(String $factionName, Int $balance) : void {
        self::setBalance($factionName, self::getBalance($factionName) + $balance);
    }

    /**
     * @param String $factionName
     * @param Int $balance
     * @return void
     */
    public static function reduceBalance(String $factionName, Int $balance) : void {
        self::setBalance($factionName, self::getBalance($factionName) - $balance);
    }

    /**
     * @param String $factionName
     * @param Int $balance
     * @return void
     */
    public static function setBalance(String $factionName, Int $balance) : void {
        $data = SQLite3Provider::getDataBase()->prepare("INSERT OR REPLACE INTO balance(factionName, money) VALUES (:factionName, :money);");
        $data->bindValue(":factionName", $factionName);
		$data->bindValue(":money", $balance);
		$data->execute();
    }

    /**
     * @param String $factionName
     * @return String|null 
     */
    public static function getFactionHomeString(String $factionName) : ?String {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM homes WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
			return "They don't have a hq";
		}
        return "X: ".$result["x"]." Y: ".$result["y"]." Z: ".$result["z"];
    }

    /**
     * @param String $factionName
     * @return bool
     */
    public static function isHome(String $factionName) : bool {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM homes WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(!empty($result)){
        	$data->finalize();
            return true;
        }else{
        	$data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }

    /**
     * @param String $factionName
     * @return Position|null
     */
     # NOTE: Here we use Position and not Vector3 because Position has the function of level and Vector3 does not
    public static function getFactionHomeLocation(String $factionName) : ?Position {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM homes WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
			return null;
		}
        $level = Loader::getInstance()->getServer()->getLevelByName($result["level"]);
        
        $level->loadChunk($result["x"], $result["z"]);
        return new Position($result["x"], $result["y"], $result["z"], $level);
    }

    /**
     * @param String $factionName
     * @param String $level
     * @param Vector3 $position
     */
    public static function setFactionHome(String $factionName, String $level, Vector3 $position) : void {
        $data = SQLite3Provider::getDataBase()->prepare("INSERT OR REPLACE INTO homes(factionName, level, x, y, z) VALUES (:factionName, :level, :x, :y, :z);");
		$data->bindValue(":factionName", $factionName);
        $data->bindValue(":level", $level);
		$data->bindValue(":x", $position->getFloorX());
		$data->bindValue(":y", $position->getFloorY());
		$data->bindValue(":z", $position->getFloorZ());
        $data->execute();
    }
    
    /**
     * @param String $factionName
     * @param Int $time
     * @return void
     */
    public static function setFreezeTime(String $factionName, Int $time = 0) : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."TimeFreeze.yml", Config::YAML);
        $file->set($factionName, $time);
        $file->save();
    }

    /**
     * @param String $factionName
     * @return Int
     */
    public static function getFreezeTime(String $factionName) : ?Int {
        $file = new Config(Loader::getInstance()->getDataFolder()."TimeFreeze.yml", Config::YAML);
        return $file->get($factionName);
    }

    /**
     * @param String $factionName
     * @return bool
     */
    public static function isFreezeTime(String $factionName) : bool {
        $file = new Config(Loader::getInstance()->getDataFolder()."TimeFreeze.yml", Config::YAML);
        if($file->exists($factionName)){
            return true;
        }else{
            return false;
        }
        return false;
    }

    /**
     * @param String $factionName
     * @return void
     */
    public static function removeFreezeTime(String $factionName) : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."TimeFreeze.yml", Config::YAML);
        if(self::isFreezeTime($factionName)){
            $file->remove($factionName);
            $file->save();
        }
    }

    /**
     * @param String $factionName
     * @param Level|null $level
     * @param Array $position1
     * @param Array $position2
     * @param String $protection
     */
    public static function claimRegion(String $factionName, ?String $level, Array $position1, Array $position2, String $protection = "Faction") : void {
        $xMin = min($position1[0], $position2[0]);
		$xMax = max($position1[0], $position2[0]);
		
		$zMin = min($position1[2], $position2[2]);
		$zMax = max($position1[2], $position2[2]);
		
		$yMin = min(0, 250);
        $yMax = max(0, 250);

        $data = SQLite3Provider::getDataBase()->prepare("INSERT OR REPLACE INTO zoneclaims(factionName, protection, x1, z1, x2, z2, level) VALUES (:factionName, :protection, :x1, :z1, :x2, :z2, :level);");
        $data->bindValue(":factionName", $factionName);
        $data->bindValue(":protection", $protection);
        $data->bindValue(":x1", $xMin);
        $data->bindValue(":z1", $zMin);
        $data->bindValue(":x2", $xMax);
        $data->bindValue(":z2", $zMax);
        $data->bindValue(":level", $level);
        $data->execute();
    }

    /**
     * @param Vector3 $position
     * @return void
     */
    public static function isSpawnRegion(Vector3 $position) : bool {
        $x = $position->getFloorX();
        $z = $position->getFloorZ();
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE $x >= x1 AND $x <= x2 AND $z >= z1 AND $z <= z2 AND protection = 'Spawn';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(!empty($result) and $position->getLevel()->getName() === $result["level"]){
        	$data->finalize();
            return true;
        }else{
        	$data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }

    /**
     * @param Vector3 $position
     * @return void
     */
    public static function isProtectedRegion(Vector3 $position) : bool {
        $x = $position->getFloorX();
        $z = $position->getFloorZ();
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE $x >= x1 AND $x <= x2 AND $z >= z1 AND $z <= z2 AND protection = 'Protection';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(!empty($result) and $position->getLevel()->getName() === $result["level"]){
        	$data->finalize();
            return true;
        }else{
        	$data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }

    /**
     * @param Vector3 $position
     * @return void
     */
    public static function isFactionRegion(Vector3 $position) : bool {
        $x = $position->getFloorX();
        $z = $position->getFloorZ();
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE $x >= x1 AND $x <= x2 AND $z >= z1 AND $z <= z2 AND protection = 'Faction';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result) === false){
        	if(self::getStrength($result["factionName"]) < 1){
        		$data->finalize();
        		return false;
        	}else{
        		$data->finalize();
            	return true;
            }
        }else{
        	$data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }

    /**
     * @param Vector3 $position
     * @return String|null
     */
    public static function getRegionName(Vector3 $position) : ?String {
        $x = $position->getFloorX();
        $z = $position->getFloorZ();
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE $x >= x1 AND $x <= x2 AND $z >= z1 AND $z <= z2;");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
			return null;
		}
        if($position->getLevel()->getName() === $result["level"]){
        	return $result["factionName"];
        }else{
        	$data->finalize();
        	return null;
        }
    }
    
    /**
     * @param String $factionName
     * @return bool
     */
    public static function isRegionExists(String $factionName) : bool {
    	$data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE factionName = '$factionName';");
		$result = $data->fetchArray(SQLITE3_ASSOC);
		if(!empty($result)){
			$data->finalize();
			return true;
		}else{
			$data->finalize();
			return false;
		}
		$data->finalize();
		return false;
    }

    /**
     * @param String $factionName
     * @return void
     */
    public static function removeRegion(String $factionName) : void {
        SQLite3Provider::getDataBase()->query("DELETE FROM zoneclaims WHERE factionName = '$factionName';");
    }
    
    /**
	 * @param Player $player
	 * @param Block $block
	 */
	public static function seeRegions(Player $player, Block $block){
	    $factionName = self::getFaction($player->getName());

        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM zoneclaims WHERE factionName = '$factionName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        $position1 = new Vector3($result["x1"], $player->getFloorY(), $result["z1"]);
        $position2 = new Vector3($result["x2"], $player->getFloorY(), $result["z2"]);
        $position3 = new Vector3($result["x1"], $player->getFloorY(), $result["z2"]);
        $position4 = new Vector3($result["x2"], $player->getFloorY(), $result["z1"]);
        for($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++){
            $pk = new UpdateBlockPacket();
            $pk->x = $position1->getFloorX();
            $pk->y = $i;
            $pk->z = $position1->getFloorZ();
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $block->getRuntimeId();
            $player->dataPacket($pk);
        }
        for($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++){
            $pk = new UpdateBlockPacket();
            $pk->x = $position2->getFloorX();
            $pk->y = $i;
            $pk->z = $position2->getFloorZ();
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $block->getRuntimeId();
            $player->dataPacket($pk);
        }
        for($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++){
            $pk = new UpdateBlockPacket();
            $pk->x = $position3->getFloorX();
            $pk->y = $i;
            $pk->z = $position3->getFloorZ();
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $block->getRuntimeId();
            $player->dataPacket($pk);
        }
        for($i = $player->getFloorY(); $i < $player->getFloorY() + 40; $i++){
            $pk = new UpdateBlockPacket();
            $pk->x = $position4->getFloorX();
            $pk->y = $i;
            $pk->z = $position4->getFloorZ();
            $pk->flags = UpdateBlockPacket::FLAG_ALL;
            $pk->blockRuntimeId = $block->getRuntimeId();
            $player->dataPacket($pk);
        }
        $data->finalize();
    }

    /**
     * @param String $spawnName
     * @return Position|null
     */
    # NOTE: Here we use Position and not Vector3 because Position has the function of level and Vector3 does not
    public static function getSpawnLocation(String $spawnName) : ?Position {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM spawns WHERE spawnName = '$spawnName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
            return null;
        }
        $level = Loader::getInstance()->getServer()->getLevelByName($result["level"]);
        
        $level->loadChunk($result["x"], $result["z"]);
        return new Position($result["x"], $result["y"], $result["z"], $level);
    }

    /**
     * @param String $spawnName
     * @return bool
     */
    public static function isSpawn(String $spawnName) : bool {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM spawns WHERE spawnName = '$spawnName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(!empty($result)){
            $data->finalize();
            return true;
        }else{
            $data->finalize();
            return false;
        }
        $data->finalize();
        return false;
    }

    /**
     * @param String $spawnName
     * @return String|null
     */
    public static function getSpawnString(String $spawnName) : ?String {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM spawns WHERE spawnName = '$spawnName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
            return "The spawn you are looking for does not exist";
        }
        return "X: ".$result["x"]." Y: ".$result["y"]." Z: ".$result["z"];
    }

    /**
     * @param String $spawnName
     * @param String $level
     * @param Vector3 $position
     */
    public static function setSpawn(String $spawnName, String $level, Vector3 $position) : void {
        $data = SQLite3Provider::getDataBase()->prepare("INSERT OR REPLACE INTO spawns(spawnName, level, x, y, z) VALUES (:spawnName, :level, :x, :y, :z);");
        $data->bindValue(":spawnName", $spawnName);
        $data->bindValue(":level", $level);
        $data->bindValue(":x", $position->getFloorX());
        $data->bindValue(":y", $position->getFloorY());
        $data->bindValue(":z", $position->getFloorZ());
        $data->execute();
    }

    /**
     * @param String $spawnName
     * @return void
     */
    public static function removeSpawn(String $spawnName) : void {
        SQLite3Provider::getDataBase()->query("DELETE FROM spawns WHERE spawnName = '$spawnName';");
    }
    
    public static function getSubclaim(\pocketmine\level\Position $position): ?string
    {
        [$x, $y, $z, $levelName] = [$position->getFloorX(), $position->getFloorY(), $position->getFloorZ(), $position->getLevel()->getName()];
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM subclaims WHERE x = '$x' AND y = '$y' AND z = '$z' AND world = '$levelName';");
        $result = $data->fetchArray(SQLITE3_ASSOC);
        
        if (empty($result))
            return null;
        return $result['owner'];
    }
    
    public static function addSubclaim(string $owner, \pocketmine\level\Position $position): void
    {
        [$x, $y, $z, $levelName] = [$position->getFloorX(), $position->getFloorY(), $position->getFloorZ(), $position->getLevel()->getName()];
        $data = SQLite3Provider::getDataBase()->prepare('INSERT INTO subclaims(x, y, z, world, owner) VALUES(:x, :y, :z, :world, :owner);');
        $data->bindValue(':x', $x);
        $data->bindValue(':y', $y);
        $data->bindValue(':z', $z);
        $data->bindValue(':world', $levelName);
        $data->bindValue(':owner', $owner);
        $data->execute();
    }
    
    public static function removeSubclaim(\pocketmine\level\Position $position): void
    {
        [$x, $y, $z, $levelName] = [$position->getFloorX(), $position->getFloorY(), $position->getFloorZ(), $position->getLevel()->getName()];
        SQLite3Provider::getDataBase()->query("DELETE FROM subclaims WHERE x = '$x' AND y = '$y' AND z = '$z' AND world = '$levelName';");
    }
    
    # Poner puntos a una faction (No suma)
    public static function setPoints(string $factionName, int $points): void
    {
        $data = SQLite3Provider::getDataBase()->prepare('INSERT OR REPLACE INTO points(factionName, points) VALUES (:factionName, :points);');
        $data->bindValue(':factionName', $factionName);
        $data->bindValue(':points', $points);
        $data->execute();
    }
     
     # Obtener los puntos de alguna faction.
    public static function getPoints(String $factionName) : Int {
        $data = SQLite3Provider::getDataBase()->query("SELECT * FROM points WHERE factionName = '$factionName';");
		$result = $data->fetchArray(SQLITE3_ASSOC);
		if(empty($result)){
			return 0;
		}
		return $result["points"];
    }
    
    # Agrega puntos a una faction
    public static function addPoints(String $factionName, Int $points) : void {
        self::setPoints($factionName, self::getPoints($factionName) + $points);
    }
    # Elimina los puntos de una faction.
    public static function delPoints(String $factionName, Int $points) : void {
        self::setPoints($factionName, self::getPoints($factionName) - $points);
    }
    
    # Top 10 de las factions con mas puntos
    public static function getTopFactions(){
      $data = SQLite3Provider::getDataBase();
      $result = $data->query("SELECT * FROM points ORDER BY points DESC LIMIT 10;");
      $count = 1;
      $text = "§l§cPremio a Top 1: §f$5 USD §9Pay§bPal\n\n§r§6Top Factions:\n";
      
      while($resultArr = $result->fetchArray(SQLITE3_ASSOC)) {
          $name = $resultArr["factionName"];
          $points = self::getPoints($name);
      
          if($count > 10)
            break;
        $text .= "§e#{$count}§f - {$name} §f: §b{$points}\n";
      
      $count++;
    }
    return $text;
    }
    }

?>