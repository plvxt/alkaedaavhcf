<?php

declare(strict_types=1);


namespace alkaedaav\keyall;


class Keyall {

    /** @var bool */
    static private $enabled = false;

    static public function isEnabled(): bool {
        return self::$enabled;
    }

    static public function enable(): void {

        self::$enabled = true;
    }

    static public function disable(): void {
        self::$enabled = false;
    }

}