<?php

namespace LanguageAPI\LanguageAPI\utils;

use LanguageAPI\LanguageAPI\Main;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Utils{

    public static function getPlayerLanguage(Player $player): string{
        $config = new Config(Main::$playerPath . "{$player->getName()}.yml", Config::YAML);
        return $config->get("language");
    }
}