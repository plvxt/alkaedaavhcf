<?php

namespace alkaedaav\shop;

use pocketmine\math\Vector3;

class Shop {

    /** @var String */
    protected $type;

    /** @var Int */
    protected $id;

    /** @var Int */
    protected $damage;

    /** @var Int */
    protected $amount;

    /** @var Int */
    protected $price;

    /** @var Vector3 */
    protected $position;

    /**
     * Shop Constructor.
     * @param String $type
     * @param Int $id
     * @param Int $damage
     * @param Int $amount
     * @param Int $price
     * @param String $position
     */
    public function __construct(String $type, Int $id, Int $damage, Int $amount, Int $price, String $position){
        $this->type = $type;
        $this->id = $id;
        $this->damage = $damage;
        $this->amount = $amount;
        $this->price = $price;
        $this->position = $position;
    }

    /**
     * @return String
     */
    public function getType() : String {
        return $this->type;
    }

    /**
     * @param String $type
     */
    public function setType(String $type){
        $this->type = $type;
    }

    /**
     * @return Int
     */
    public function getId() : Int {
        return $this->id;
    }

    /**
     * @param Int $id
     */
    public function setId(Int $id){
        $this->id = $id;
    }

    /**
     * @return Int
     */
    public function getDamage() : Int {
        return $this->damage;
    }

    /**
     * @param Int $damage
     */
    public function setDamage(Int $damage){
        $this->damage = $damage;
    }

    /**
     * @return Int
     */
    public function getAmount() : Int {
        return $this->amount;
    }

    /**
     * @param Int $amount
     */
    public function setAmount(Int $amount){
        $this->amount = $amount;
    }

    /**
     * @return Int
     */
    public function getPrice() : Int {
        return $this->price;
    }

    /**
     * @param Int $price
     */
    public function setPrice(Int $price){
        $this->price = $price;
    }

    /**
     * @return String
     */
    public function getPosition() : String {
        return $this->position;
    }

    /**
     * @param String $position
     */
    public function setPosition(String $position){
        $this->position = $position;
    }
}

?>