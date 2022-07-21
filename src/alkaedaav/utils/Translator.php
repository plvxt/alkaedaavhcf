<?php

namespace alkaedaav\utils;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use pocketmine\item\Item;

use pocketmine\math\Vector3;

class Translator {

    const VALID_FORMATS = ["minutes", "hours", "seconds", "days"];

    /** @var Array[]  */
    protected static $effects = [
        Effect::SPEED => ["name" => "Speed"],
        Effect::SLOWNESS => ["name" => "Slowness"],
        Effect::FATIGUE => ["name" => "Fatigue"],
        Effect::STRENGTH => ["name" => "Strength"],
        Effect::INSTANT_HEALTH => ["name" => "Instant Health"],
        Effect::INSTANT_DAMAGE => ["name" => "Instant Damage"],
        Effect::JUMP_BOOST => ["name" => "Jump Boost"],
        Effect::NAUSEA => ["name" => "Nausea"],
        Effect::REGENERATION => ["name" => "Regeneration"],
        Effect::RESISTANCE => ["name" => "Resistance"],
        Effect::FIRE_RESISTANCE => ["name" => "Fire Resistance"],
        Effect::WATER_BREATHING => ["name" => "Water Breathing"],
        Effect::INVISIBILITY => ["name" => "Invisibility"],
        Effect::BLINDNESS => ["name" => "Blindness"],
        Effect::NIGHT_VISION => ["name" => "Nigth Vision"],
        Effect::HUNGER => ["name" => "Hunger"],
        Effect::WEAKNESS => ["name" => "Weakness"],
        Effect::POISON => ["name" => "Poison"],
        Effect::WITHER => ["name" => "Wither"],
        Effect::HEALTH_BOOST => ["name" => "Health Boost"],
        Effect::ABSORPTION => ["name" => "Absorption"],
        Effect::SATURATION => ["name" => "Saturation"],
        Effect::LEVITATION => ["name" => "Levitation"],
        Effect::FATAL_POISON => ["name" => "Fatal Poison"],
        Effect::CONDUIT_POWER => ["name" => "Conduit Power"],
    ];

    /**
     * @param EffectInstance $effectInstance
     * @return String
     */
    public static function effectToStringByObject(EffectInstance $effectInstance) : String {
        if(isset(self::$effects[$effectInstance->getId()])){
            return self::$effects[$effectInstance->getId()]["name"];
        }
    }

    /**
     * @param Vector3 $vector3
     * @return Array
     */
    public static function vector3ToArray(Vector3 $vector3) : Array {
        $value = [$vector3->x, $vector3->y, $vector3->z];
        return $value;
    }

    /**
     * @param Vector3 $position
     * @return String
     */
    public static function vector3ToString(Vector3 $position) : String {
        return "$position->x, $position->y, $position->z";
    }

    /**
     * @param array $vector3
     * @return Vector3
     */
    public static function arrayToVector3(Array $vector3) : ?Vector3 {
        if(count($vector3) < 2) return null;
        $value = new Vector3($vector3[0], $vector3[1], $vector3[2]);
        return $value;
    }

    /**
     * @param String $item
     * @param Int $amount
     * @return Item
     */
    public static function itemStringToObject(String $item, Int $amount) : Item {
        list($id, $damage) = explode(":", $item);
        return Item::get($id, $damage, $amount);
    }

    /**
     * @param String $format
     * @return Int
     */
    public static function stringToInt(String $format) : Int {
        $result = str_split($format);
        $characters = "";
        for($i = 0; $i < count($result); $i++){
            if(is_numeric($result[$i])){
            	$characters .= $result[$i];
            	continue;
            }
        }
        return $characters;
    }

    /**
     * @param String $format
     */
    public static function intToString(String $format){
        $result = str_split($format);
        $time = null; 
        for($i = 0; $i < count($result); $i++){
            switch($result[$i]){
                case "m":
                $time = "minutes";
                break;
                case "h":
                $time = "hours";
                break;
                case "d":
                $time = "days";
                break;
                case "s":
                $time = "seconds";
                break;
            }
        }
        return $time;
    }

    /**
     * @param Int $time
     * @param String $format
     * @return Int
     */
    public static function getStringFormatToInt(Int $time, String $format) : Int {
        $value = null;
        switch(self::intToString($format)){
            case "minutes":
            $value = $time * 60 + 1;
            break;
            case "hours":
            $value = $time * 3600 + 1;
            break;
            case "days":
            $value = $time * 86400 + 1;
            break;
            case "seconds":
            $value = $time * 1 + 1;
            break;
        }
        return $value;
    }
}

?>