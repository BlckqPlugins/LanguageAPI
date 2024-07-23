<?php
declare(strict_types=1);
namespace Frago9876543210\EasyForms\elements;
use Core\Core\api\LanguageAPI;
use pocketmine\player\Player;
use Core\Core\CorePlayer;


/**
 * Class CategoryButton
 * @package Frago9876543210\EasyForms\elements
 * @author Florian H.
 * @date 27.06.2020 - 13:34
 * @project BedWars
 */
class CategoryButton extends Button{
	/** @var string */
	protected $name;
	/** @var string */
	protected $categoryId;


	/**
	 * CategoryButton constructor.
	 * @param Player $player
	 * @param string $name
	 * @param string $categoryId
	 */
	public function __construct(Player $player, string $name, string $categoryId)
    {
        if (!$player instanceof CorePlayer) {
            return;
        }
        parent::__construct("§8" . LanguageAPI::translateMessage($player, $name));
        $this->categoryId = $categoryId;
    }

	/**
	 * Function getCategoryId
	 * @return string
	 */
	public function getCategoryId(): string{
		return $this->categoryId;
	}
}