<?php

declare(strict_types=1);


namespace alkaedaav\keyall;


use EasyUI\element\Input;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use alkaedaav\crate\CrateManager;

class KeyallForm extends CustomForm {

    public function __construct() {
        parent::__construct("Set a keyall timer");
    }

    protected function onCreation(): void {
        $this->addElement("time", new Input("Tiempo: (en segundos)"));
        foreach(CrateManager::getCrates() as $crate) {
            $this->addElement($key = $crate->getKey(), new Input("La cantidad de $key keys que dará el keyall:"));
        }
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        if(!$player instanceof \alkaedaav\player\Player) {
            return;
        }
        $time = $response->getInputSubmittedText("time");
        if(!is_numeric($time) or $time <= 0) {
            $player->sendMessage(TextFormat::RED . "Has puesto un número inválido!");
            return;
        }
        foreach(CrateManager::getCrates() as $crate) {

            if(is_numeric($response->getInputSubmittedText($crate->getKey()))) {
                Keyall::enable();
            }
        }
    }

}