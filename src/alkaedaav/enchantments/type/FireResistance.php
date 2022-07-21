<?php

namespace alkaedaav\enchantments\type;

use pocketmine\utils\TextFormat as TE;

use alkaedaav\enchantments\CustomEnchantment;

use pocketmine\entity\{Effect, EffectInstance};

class FireResistance extends CustomEnchantment {

    /**
     * FireResistanceEnchantment Constructor.
     */
    public function __construct(){
        parent::__construct($this->getId(), $this->getName(), self::RARITY_COMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2);
    }

    /**
     * @return Int
     */
    public function getId() : Int {
        return 38;
    }

    /**
     * @return String
     */
    public function getName() : String {
        return "Fire Resistance";
    }
    
    /**
     * @return String
     */
    public function getNameWithFormat() : String {
    	return TE::RESET.TE::GOLD."Fire Resistance II";
    }

        /**
     * @return EffectInstance
     */
    public function getEffectsByEnchantment() : EffectInstance {
        return new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 60, ($this->getMaxLevel() - 1));
    }
    
    /**
     * @return Int
     */
    public function getEnchantmentPrice() : Int {
    	return 10000;
   }
}

?>