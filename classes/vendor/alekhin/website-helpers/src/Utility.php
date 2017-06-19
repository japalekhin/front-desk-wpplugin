<?php

namespace Alekhin\WebsiteHelpers;

if (!class_exists(__NAMESPACE__ . '\Utility')) {

    class Utility {

        static private function s4() {
            return substr(dechex(intval((1 + (rand(0, 9999) / 10000)) * 65536)), 1);
        }

        static function uuid() {
            return self::s4() . self::s4() . '-' . self::s4() . '-4' . substr(self::s4(), 1) . '-' . self::s4() . '-' . self::s4() . self::s4() . self::s4();
        }

    }

}
