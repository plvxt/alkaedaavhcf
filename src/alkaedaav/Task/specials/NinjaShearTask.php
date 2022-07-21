<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace alkaedaav\Task\specials;


use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use alkaedaav\Loader;
use alkaedaav\player\Player;

class NinjaShearTask extends Task {

    /** @var Player */
    private $player;

    /** @var int */
    private $time = 4;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(int $currentTick): void {
        $this->time--;
        if($this->time <= 0) {
            $this->player->teleport($this->player->getNinjaShearPosition());
            $this->player->setNinjaShearPosition(null);
            Loader::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }
        $this->player->sendMessage(TextFormat::YELLOW . "Teleporting in " . $this->time . " seconds...");
    }

}