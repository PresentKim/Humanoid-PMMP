<?php

namespace kim\presenthumanoid\act\child;

use kim\presenthumanoid\act\{
	InteractAct, PlayerAct
};
use kim\presenthumanoid\Humanoid as Plugin;
use kim\presenthumanoid\util\Translation;
use pocketmine\entity\Entity;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\{
	CompoundTag, DoubleTag, ListTag
};
use pocketmine\Player;

class PasteHumanoidAct extends PlayerAct implements InteractAct{

	/** @var CompoundTag */
	private $nbt;

	/**
	 * @param Player      $player
	 * @param CompoundTag $nbt
	 */
	public function __construct(Player $player, CompoundTag $nbt){
		parent::__construct($player);
		$this->nbt = $nbt;
	}

	/** @param PlayerInteractEvent $event */
	public function onInteract(PlayerInteractEvent $event) : void{
		$pos = $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR ? $this->player->asPosition() : $pos = $event->getBlock();
		$this->nbt->setTag(new ListTag("Pos", [
			new DoubleTag("", $pos->x),
			new DoubleTag("", $pos->y),
			new DoubleTag("", $pos->z),
		]));

		$entity = Entity::createEntity('Humanoid', $pos->level, $this->nbt);
		$entity->spawnToAll();

		$this->player->sendMessage(Plugin::$prefix . Translation::translate('humanoid-copy@success-paste'));

		$event->setCancelled(true);
		$this->cancel();
	}
}