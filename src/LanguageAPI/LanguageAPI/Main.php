<?php

namespace LanguageAPI\LanguageAPI;

use LanguageAPI\LanguageAPI\api\LanguageAPI;
use LanguageAPI\LanguageAPI\listener\EventListener;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase{

    protected static ?self $instance = null;
    protected static ?LanguageAPI $languageAPI = null;
    public static ?string $playerPath = null;
    public static ?string $defaultLanguage = null;
    public static ?string $githubURL = null;
    public static array $languages = [];

    protected function onEnable(): void
    {
        $this->saveResource("config.yml");

        self::$instance = $this;

        @mkdir($this->getDataFolder());
        @mkdir($this->getDataFolder() . "players/");

        $config = $this->getConfig();
        self::$githubURL = $config->get("github-url");

        if (is_array($config->get("languages"))){
            self::$languages = $config->get("languages");
        } else {
            $config->set("languages", []);
            $config->save();
        }

        self::$languageAPI = new LanguageAPI();

        if (in_array($config->get("default-lang"), self::$languages)){
            self::$defaultLanguage = $config->get("default-lang");
        } else {
            Server::getInstance()->getLogger()->error("The default language isn't loaded, disable plugin.");
            Server::getInstance()->getPluginManager()->disablePlugin($this);
            return;
        }

        if (is_null($config->get("player_path"))){
            Server::getInstance()->getLogger()->error("§cPlayer path is null, setting to default.");

            $config->set("player_path", "{$this->getDataFolder()}players/");
            $config->save();

            self::$playerPath = $this->getDataFolder() . "players/";
        } else if ($config->get("player_path") === "default"){
            self::$playerPath = $this->getDataFolder() . "players/";
        } else if (!is_dir(self::$playerPath)){
            Server::getInstance()->getLogger()->error("§cPlayer path isn't a directory, setting to default.");

            $config->set("player_path", "{$this->getDataFolder()}players/");
            $config->save();

            self::$playerPath = $this->getDataFolder() . "players/";
        } else {
            self::$playerPath = $config->get("player_path");
        }


        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    /**
     * @return Main|null
     */
    public static function getInstance(): ?Main
    {
        return self::$instance;
    }

    /**
     * @return LanguageAPI|null
     */
    public static function getLanguageAPI(): ?LanguageAPI
    {
        return self::$languageAPI;
    }
}