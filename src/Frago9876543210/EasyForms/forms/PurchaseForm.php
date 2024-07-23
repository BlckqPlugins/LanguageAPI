<?php
declare(strict_types=1);
namespace Frago9876543210\EasyForms\forms;
use Closure;
use Coins\Coins\Main;
use Core\Core\api\LanguageAPI;
use Core\Core\CorePlayer;
use pocketmine\{form\FormValidationException, player\Player, utils\Utils};

use function gettype;
use function is_bool;


class PurchaseForm extends Form{
	/** @var string */
	protected $text;
	/** @var int */
	private $price;
	/** @var string */
	private $yesButton;
	/** @var string */
	private $noButton;
	/** @var Closure */
	protected $onSubmit;


	/**
	 * PurchaseForm constructor.
	 * @param CorePlayer $player
	 * @param string $title
	 * @param string $text
	 * @param int $price
	 * @param Closure $onBuy
	 */
	public function __construct(CorePlayer $player, string $title, string $text, int $price, Closure $onBuy){
		parent::__construct($title);
		$this->text = $text;
		$this->price = (int)$price;
		$this->yesButton = LanguageAPI::translateMessage($player, "ui.button.buy");
		$this->noButton = LanguageAPI::translateMessage($player, "ui.button.cancel");
		Utils::validateCallableSignature(function (CorePlayer $player): void{}, $onBuy);
		$this->onSubmit = function (CorePlayer $player, int $price, bool $response) use ($onBuy): void{
			if ($response) {
				if (Main::getDatabase()->getCoins($player->getName()) >= $price) {
					Main::getDatabase()->rmCoins($player->getName(), $price);
					$onBuy($player);
				} else {
					LanguageAPI::sendMessage($player, "message.notEnoughCoins");
				}
			}
		};
	}

	/**
	 * Function createConfirmForm
	 * @param string $title
	 * @param string $text
	 * @param int $price
	 * @param Closure $onConfirm
	 * @return static
	 */
	public static function createConfirmForm(string $title, string $text, int $price, Closure $onConfirm): self{
		Utils::validateCallableSignature(function (CorePlayer $player): void{}, $onConfirm);
		return new self($title, $text, $price, function (CorePlayer $player, int $price, bool $response) use ($onConfirm): void{
			if ($response) {
				$onConfirm($player);
			}
		});
	}

	/**
	 * @return string
	 */
	final public function getType(): string{
		return self::TYPE_MODAL;
	}

	/**
	 * Function getPrice
	 * @return int
	 */
	public function getPrice(): int{
		return $this->price;
	}

	/**
	 * @return string
	 */
	public function getYesButtonText(): string{
		return $this->yesButton;
	}

	/**
	 * @return string
	 */
	public function getNoButtonText(): string{
		return $this->noButton;
	}

	/**
	 * @return array
	 */
	protected function serializeFormData(): array{
		return [
			"content" => $this->text,
			"button1" => $this->yesButton,
			"button2" => $this->noButton,
		];
	}

	/**
	 * Function handleResponse
	 * @param Player $player
	 * @param mixed $data
	 * @return void
	 */
	final public function handleResponse(Player $player, $data): void{
		if (!is_bool($data)) {
			throw new FormValidationException("Expected bool, got " . gettype($data));
		}
		($this->onSubmit)($player, $this->price, $data);
	}
}