<?php

namespace alkaedaav\citadel;

use alkaedaav\player\Player;
use alkaedaav\utils\Translator;

use pocketmine\math\Vector3;

class Citadel {

    /** @var String */
    protected $name;

    /** @var Player */
    protected $capturer;

    /** @var Int */
    protected $citadelTime;

    /** @var bool */
    protected $capture = false;

    /** @var array[] */
    protected $position1 = [];

    /** @var array[] */
    protected $position2 = []; 

    /** @var String */
    protected $level;

    /** @var bool */
    protected $enable = false;

    /**
     * Koth Constructor.
     * @param String $name
     * @param array $position1
     * @param array $position2
     */
    public function __construct(String $name, Array $position1, Array $position2, String $level){
        $this->name = $name;
        $this->level = $level;
        $this->position1 = $position1;
        $this->position2 = $position2;
    }

    /**
     * @return String
     */
    public function getName() : String {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(String $name){
        $this->name = $name;
    }

    /**
     * @return String
     */
    public function getLevel() : String {
        return $this->level;
    }

    /**
     * @param String $level
     */
    public function setLevel(String $level){
        $this->level = $level;
    }

    /**
     * @return Int
     */
    public function getCitadelTime() : Int {
        return $this->citadelTime;
    }
    
    /**
     * @param Int $citadelTime
     */
    public function setCitadelTime(Int $citadelTime){
        $this->citadelTime = $citadelTime;
    }

    /**
     * @return Int
     */
    public function getDefaultCitadelTime() : Int {
        return 15 * 60 + 1;
    }

    /**
     * @return Vector3
     */
    public function getPosition1() : Vector3 {
        return Translator::arrayToVector3($this->position1);
    }

    /**
     * @param Vector3 $position1
     */
    public function setPosition1(Vector3 $position1){
        $this->position1 = Translator::vector3ToArray($position1);
    }

    /**
     * @return Vector3
     */
    public function getPosition2() : Vector3 {
        return Translator::arrayToVector3($this->position2);
    }

    /**
     * @param Vector3 $position2
     */
    public function setPosition2(Vector3 $position2){
        $this->position2 = Translator::vector3ToArray($position2);
    }

    /**
     * @param Vector3 $player
     * @return bool
     */
    public function isInPosition(Vector3 $position) : bool {
        $x = $position->getFloorX();
        $y = $position->getFloorY();
        $z = $position->getFloorZ();

        $xMin = min($this->getPosition1()->getFloorX(), $this->getPosition2()->getFloorX());
        $xMax = max($this->getPosition1()->getFloorX(), $this->getPosition2()->getFloorX());

        $yMin = min($this->getPosition1()->getFloorY(), $this->getPosition2()->getFloorY());
        $yMax = max($this->getPosition1()->getFloorY(), $this->getPosition2()->getFloorY());

        $zMin = min($this->getPosition1()->getFloorZ(), $this->getPosition2()->getFloorZ());
        $zMax = max($this->getPosition1()->getFloorZ(), $this->getPosition2()->getFloorZ());

        return $x >= $xMin && $x <= $xMax && $y >= $yMin && $y <= $yMax && $z >= $zMin && $z <= $zMax; 
    }

    /**
     * @return bool
     */
    public function isCapture() : bool {
        return $this->capture;
    }

    /**
     * @param bool $capture
     */
    public function setCapture(bool $capture){
        $this->capture = $capture;
    }

    /**
     * @return bool
     */
    public function isEnable() : bool {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable){
        $this->enable = $enable;
    }

    /**
     * @param Player $player
     */
    public function setCapturer(?Player $player){
        $this->capturer = $player;
    }

    /**
     * @return Player
     */
    public function getCapturer() : ?Player {
        return $this->capturer;
    }
}

?>