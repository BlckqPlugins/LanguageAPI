<?php

namespace LanguageAPI\LanguageAPI\commands;

use LanguageAPI\LanguageAPI\api\LanguageAPI;
use LanguageAPI\LanguageAPI\Main;
use LanguageAPI\LanguageAPI\utils\Language;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class LanguageCommand extends Command{

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setPermission("language.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) return;
        Main::getLanguageAPI()->openForm($sender);
    }
}