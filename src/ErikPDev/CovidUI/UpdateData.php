<?php

namespace ErikPDev\CovidUI;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;

class UpdateData extends AsyncTask{


	public function __construct(){}

	public function onRun() : void{
		$result = Internet::getURL("https://disease.sh/v3/covid-19/countries/");
        if ($result == false){return;}
		$this->setResult($result);
	}

	public function onCompletion(Server $server) : void{
		$plugin = Server::getInstance()->getPluginManager()->getPlugin("CovidUI");

		if($plugin === null){
			return;
		}

		$result = $this->getResult();
        \ErikPDev\CovidUI\Main::getInstance()->UpdateData(json_decode($result,true));   
	}
}