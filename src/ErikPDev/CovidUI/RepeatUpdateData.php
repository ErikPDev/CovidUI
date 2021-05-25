<?php

namespace ErikPDev\CovidUI;
use pocketmine\utils\Internet;
use pocketmine\Server;
class RepeatUpdateData extends \pocketmine\scheduler\Task{
    public function onRun(int $currentTick) : void{
      Server::getInstance()->getAsyncPool()->submitTask(new UpdateData());
    }
}