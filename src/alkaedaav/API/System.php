<?php

namespace alkaedaav\API;

use alkaedaav\{Loader, Factions};
use alkaedaav\player\Player;

use alkaedaav\utils\{Tower, Translator};

use pocketmine\utils\TextFormat as TE;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\item\{Item, ItemIds};

use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class System {

    /** @var Array[] */
    public static $cache = [];

    /**
     * @param Player $player
     * @param Int $id
     */
    public static function getPosition(Player $player, Int $id){
        if(self::isPosition($player, $id)){
            return self::$cache[$player->getName()]["position".$id];
        }
    }

    /**
     * @param Player $player
     * @param Vector3 $position
     * @param Int $id
     */
    public static function setPosition(Player $player, Vector3 $position, Int $id) : void {
        self::$cache[$player->getName()]["position".$id] = Translator::vector3ToArray($position);
    }

    /**
     * @param Player $player
     * @param Int $id
     * @return bool 
     */
    public static function isPosition(Player $player, Int $id) : bool {
        if(isset(self::$cache[$player->getName()]["position".$id][0], self::$cache[$player->getName()]["position".$id][1], self::$cache[$player->getName()]["position".$id][2])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param Player $player
     * @param Int $position
     * @param bool $tool
     * @return void
     */
    public static function deletePosition(Player $player, Int $id, bool $tool = false) : void {
        if(self::isPosition($player, $id)){
            unset(self::$cache[$player->getName()]["position".$id]);
            if($tool){
                $player->setInteract(false);
                $player->removeTool();
            }
        }   
    }
    
    /**
     * @param Player $player
     * @param Array $position1
     * @param Array $position2
     * @return void
     */
    public static function checkClaim(Player $player, Array $position1, Array $position2) : void {
        if($player->isGodMode()){
            return;
        }
    	$xMin = min($position1[0], $position2[0]);
		$xMax = max($position1[0], $position2[0]);
		
		$zMin = min($position1[2], $position2[2]);
		$zMax = max($position1[2], $position2[2]);

        $distance1 = Translator::arrayToVector3($position1);
        $distance2 = Translator::arrayToVector3($position2);
        
        if((int)$distance1->distance($distance2) > 200){
            Tower::delete($player, 1);
            Tower::delete($player, 2);
        	self::deletePosition($player, 1);
	        self::deletePosition($player, 2);
            $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_is_big")));
            return;
        }
		for($x = $xMin; $x <= $xMax; ++$x){
			for($z = $zMin; $z <= $zMax; ++$z){
				$data = Loader::getProvider()->getDataBase()->query("SELECT * FROM zoneclaims WHERE $x >= x1 AND $x <= x2 AND $z >= z1 AND $z <= z2;");
				$result = $data->fetchArray(SQLITE3_ASSOC);
				if(!empty($result)){
				    Tower::delete($player, 1);
	     		   Tower::delete($player, 2);
   		         self::deletePosition($player, 1);
       	 	    self::deletePosition($player, 2);
	       	     $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_not_claim_other_zone")));
       	     	return;
         	   }
       	 }
        }
        $finalDistance = (int)$distance1->distance($distance2);
        $player->setClaimCost($finalDistance * 40);
        $player->sendMessage(str_replace(["&", "{claimCost}"], ["§", $player->getClaimCost()], Loader::getConfiguration("messages")->get("faction_zone_cost")));
    }
}

?>