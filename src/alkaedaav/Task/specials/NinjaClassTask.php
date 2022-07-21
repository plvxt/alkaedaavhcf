<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace alkaedaav\Task\specials;


use pocketmine\scheduler\Task;
use pocketmine\Server;
use alkaedaav\player\Player;

class NinjaClassTask extends Task {

    public function onRun(int $currentTick): void {
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            if($player instanceof Player and $player->isNinjaClass() and $player->hasKatanaAbility() and $player->canEnableKatanaAbility()) {
                $player->removeKatanaAbility();
            }
        }
    }

}