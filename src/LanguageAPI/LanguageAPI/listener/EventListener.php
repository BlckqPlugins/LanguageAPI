<?php

namespace LanguageAPI\LanguageAPI\listener;

use LanguageAPI\LanguageAPI\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class EventListener implements Listener{

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        if (!is_file(Main::$playerPath . "{$name}.yml")){
            $config = new Config(Main::$playerPath . "{$name}.yml", Config::YAML);
            $config->set("language", Main::$defaultLanguage);
            $config->save();
        }
    }
}