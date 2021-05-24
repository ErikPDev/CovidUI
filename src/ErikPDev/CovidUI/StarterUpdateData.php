<?php

namespace ErikPDev\CovidUI;
use pocketmine\utils\Internet;
class StarterUpdateData extends \pocketmine\scheduler\Task{
    public function onRun(int $currentTick) : void{
      $result = Internet::getURL("https://disease.sh/v3/covid-19/countries/");
      \ErikPDev\CovidUI\Main::getInstance()->UpdateData(json_decode($result,true));
    }
}