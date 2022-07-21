<?php

namespace alkaedaav\enchantments;

use pocketmine\item\enchantment\Enchantment;

use pocketmine\entity\{Effect, EffectInstance};

abstract class CustomEnchantment extends Enchantment {

    /**
     * CustomEnchantment Constructor.
     * @param Int $id
     * @param String $name
     * @param Int $rarity
     * @param Int $primaryItemFlags
     * @param Int $secondaryItemFlags
     * @param Int $maxLevel
     */
    public function __construct(Int $id, String $name, Int $rarity, Int $primaryItemFlags, Int $secondaryItemFlags, Int $maxLevel = 1){
        parent::__construct($id, $name, $rarity, $primaryItemFlags, $secondaryItemFlags, $maxLevel);
    }

    /**
     * @return EffectInstance
     */
    abstract public function getEffectsByEnchantment() : EffectInstance;
    
    /**
     * @return Int
     */
    abstract public function getEnchantmentPrice() : Int;
    
    /**
     * @return String
     */
    abstract public function getNameWithFormat() : String;
}

?>