<?php

namespace LanguageAPI\LanguageAPI\api;

use Frago9876543210\EasyForms\elements\Button;
use Frago9876543210\EasyForms\forms\MenuForm;
use LanguageAPI\LanguageAPI\Main;
use LanguageAPI\LanguageAPI\utils\Internet;
use LanguageAPI\LanguageAPI\utils\Language;
use LanguageAPI\LanguageAPI\utils\Utils;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class LanguageAPI {

    /** @var array<Language> $languages */
    public static array $languages;

    public function __construct(){
        self::$languages = [];
        $this->loadLanguages();
    }

    public function loadLanguages(): void
    {
        $langs = Main::$languages;
        foreach ($langs as $lang) {
            $json = json_decode(Internet::getURL(Main::$githubURL . $lang . ".json"), true);
            self::$languages[$lang] = new Language($json["name"], $json["localeCode"], $json["PREFIX"], $json["values"]);
        }
    }

    public static function updateLanguage(Player $player, string $language): void
    {
        $config = new Config(Main::$playerPath . "{$player->getName()}.yml", Config::YAML);
        if (in_array($language, self::$languages)){
            $config->set("language", $language);
            $player->sendMessage("§aYou have changed your language to §e{$language}§7.");
        } else {
            $player->sendMessage("§cThis language isn't loaded§7.");
        }
    }

    public function openForm(Player $player): void
    {

        $buttons = [];
        $localCodes = [];
        foreach (self::$languages as $language){
            if (!isset($buttons[$language->getName()])) $buttons[] = $language->getName();
            if (!isset($localCodes[$language->getName()])) $localCodes[] = $language->getLocale();
        }

        $player->sendForm(new MenuForm(
            "Change your language",
            "",
            $buttons,
            function (Player $sender, Button $button) use ($player, $localCodes): void{
                self::updateLanguage($sender, $localCodes[$button->getText()]);
            }
        ));
    }

    public static function getMessage(Player $player, string $key, array $params=[]): string
    {
        if(isset(self::$languages[Utils::getPlayerLanguage($player)]->getValues()[$key])) {

            $message = self::$languages[Utils::getPlayerLanguage($player)]->getValues()[$key];

            foreach ($params as $index => $param) {
                $message = str_replace("#{$index}#", $param, $message);
            }
            $message = str_replace("{PREFIX}", self::$languages[Utils::getPlayerLanguage($player)]->getPrefix(), $message);
        } else {
            $message = "{$key}";
        }
        return $message;
    }

    public static function getMessageFromFallback(string $fallback, string $key, array $params=[]): string
    {
        if(isset(self::$languages[$fallback]->getValues()[$key])) {

            $message = self::$languages[$fallback]->getValues()[$key];

            foreach ($params as $index => $param) {
                $message = str_replace("#{$index}#", $param, $message);
            }
            $message = str_replace("{PREFIX}", self::$languages[$fallback]->getPrefix(), $message);
        } else {
            $message = "{$key}";
        }
        return $message;
    }

    /**
     * @param Player $player
     * @param string $key
     * @param array $params
     * @return void
     */
    public static function sendMessage(Player $player, string $key, array $params=[]): void
    {

        if (!$player->isConnected() or !$player->isOnline()){
            return;
        }

        $player->sendMessage(self::translateMessage($player, $key, $params));
    }

    public static function translateMessage(Player $player, string $key, array $params=[]): string
    {
        return self::getMessage($player, $key, $params);
    }

    public static function translateToFallback(string $key, array $params=[]): string
    {
        return self::getMessageFromFallback(Main::$defaultLanguage, $key, $params);
    }
}