<?php
declare(strict_types=1);
namespace Frago9876543210\EasyForms\elements;
use pocketmine\item\Item;
use Core\Core\CorePlayer;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;


/**
 * Class ChooseStickButton
 * @package Frago9876543210\EasyForms\elements
 * @author Florian H.
 * @date 25.07.2020 - 19:07
 * @project BedWars
 */
class ChooseStickButton extends Button{
	/** @var string */
	protected $itemId;
	/** @var bool */
	protected $unlocked;


	/**
	 * ChooseStickButton constructor.
	 * @param CorePlayer $player
	 * @param string $itemId
	 */
	public function __construct(CorePlayer $player, string $itemId){
		$this->itemId = $itemId;
		$item = StringToItemParser::getInstance()->parse($itemId);
		if ($player->hasUnlockedStick($itemId) || $player->hasPermission("bedwars.changeStick")) {
			$text = "§l§2» §r§8{$item->getVanillaName()}";
			$this->unlocked = true;
		} else {
			$text = "§l§c» §r§8{$item->getVanillaName()}";
			$this->unlocked = false;
		}
		parent::__construct($text, null);
	}

	/**
	 * Function getItemId
	 * @return string
	 */
	public function getItemId(): string{
		return $this->itemId;
	}

	/**
	 * Function isUnlocked
	 * @return bool
	 */
	public function isUnlocked(): bool{
		return $this->unlocked;
	}
}