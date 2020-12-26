<?php

namespace Zwuiix;

use pocketmine\utils\TextFormat;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\utils\Random;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\Item;

class Main extends PluginBase implements Listener {
	
	public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }

  CONST COOLDOWN = 10;

    private $cooldown = [];

    public function onIenteract(PlayerInteractEvent $event) {

      $b = $event->getBlock();
      $player = $event->getPlayer();
      $name = $player->getName();
      $item = $event->getItem();

            if ($item->getId() === Item::EGG) {

                if (!isset($this->cooldown[$name])) $this->cooldown[$name] = time();

                if (time() < $this->cooldown[$name]) {
                    if ($event->isCancelled()) return;
                    $event->setCancelled();
                    $second = $this->cooldown[$name] - time();
                    $player->sendPopup("§c- Attender $second seconde(s) -");
                }else {

                    $this->cooldown[$name] =  time() + self::COOLDOWN;
                    $rnd = (new Random())->nextSignedFloat() * M_PI * 2;
        $nbt = Entity::createBaseNBT($b, new Vector3(-sin($rnd) * 0, 0.4, -cos($rnd) * 0));
        $nbt->setShort("Fuse", 90);
        $tnt = Entity::createEntity("PrimedTNT", $b->getLevel(), $nbt);
        $tnt->spawnToAll();
      return true;
                }
            }
    }
	
  public function onDamage(EntityDamageEvent $event){
    $p = $event->getEntity();
      if($p instanceof Player && $event->getCause() === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION){
        switch(mt_rand(1,2)){
          case 1:
            $event->setDamage(10);
          break;
          case 2:
            $event->setDamage(8);	
          break;
          }
        }
      }
}
