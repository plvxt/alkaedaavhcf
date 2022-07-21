<?php

declare(strict_types=1);


namespace alkaedaav\keyall;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use alkaedaav\keyall\form\KeyallForm;
use alkaedaav\player\Player;

class KeyallCommand extends Command {

    public function __construct() {
        $this->setPermission("keyalltimer.admin");
        parent::__construct("keyall", "Start a keyall timer!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$this->testPermission($sender)) {
            return;
        }
        /*
        if(!$sender instanceof Player) {
            return;
        }
        $error_message = TextFormat::RED . "No puedes activar otro keyall porque ya hay uno activado!\n" . TextFormat::GRAY . '*Usa "/keyall disable" para desactivar el keyall';
        if(empty($args[0])) {
            if(Keyall::isEnabled()) {
                $sender->sendMessage($error_message);
            }
            $sender->sendForm(new KeyallForm());
            return;
        }
        if($args[0] === "disable") {
            Keyall::disable();
            $sender->sendMessage(TextFormat::GREEN . "Has desactivado el keyall correctamente!");
        } else {
            $sender->sendMessage(TextFormat::YELLOW . "/keyall (Activar keyall)");
            $sender->sendMessage(TextFormat::YELLOW . "/keyall disable (Desactivar keyall)");
        }
        */
        // TODO
    }

}