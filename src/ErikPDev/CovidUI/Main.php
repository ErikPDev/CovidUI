<?php
/**
 *  _____           _     _ _    _ _____ 
 * / ____|         (_)   | | |  | |_   _|
 *| |     _____   ___  __| | |  | | | |  
 *| |    / _ \ \ / / |/ _` | |  | | | |  
 *| |___| (_) \ V /| | (_| | |__| |_| |_ 
 * \_____\___/ \_/ |_|\__,_|\____/|_____|
 *                                       
 *                                       By @ErikPDev for PMMP
 *
 * CovidUI, a CovidInformation plugin for PocketMine-MP
 * Copyright (c) 2021 ErikPDev  < https://github.com/ErikPDev >
 *
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * VoteParty is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 * ------------------------------------------------------------------------
 */

namespace ErikPDev\CovidUI;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\CustomForm;
use ErikPDev\CovidUI\ScoreHUDListener;
use ErikPDev\CovidUI\RepeatUpdateData;
use ErikPDev\CovidUI\VersionManager;
class Main extends PluginBase implements Listener {
    public $interval,$requestedData,$WorldWideData;
    private static $instance = NULL;
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->versionManager = new VersionManager($this);

        if($this->getServer()->getPluginManager()->getPlugin("ScoreHud") != null){
          if(is_dir($this->getServer()->getPluginManager()->getPlugin("ScoreHud")->getDataFolder()."addons")){
            if( !file_exists( $this->getServer()->getPluginManager()->getPlugin("ScoreHud")->getDataFolder()."addons\VoteParty.php" ) ){
              file_put_contents( $this->getServer()->getPluginManager()->getPlugin("ScoreHud")->getDataFolder()."addons\VoteParty.php", $this->getResource('/addon/VoteParty.php'));
              $this->getLogger()->debug("Added addon to ScoreHUD");
            }
          }else{
            $this->scoreHud = new ScoreHUDListener($this);
            $this->getServer()->getPluginManager()->registerEvents($this->scoreHud, $this);
            $this->ScoreHudSupport = true;
            $this->getLogger()->debug("ScoreHud support is enabled.");
          }
        }

        Server::getInstance()->getAsyncPool()->submitTask(new Update("CovidUI", "1.1"));
        $this->saveDefaultConfig();
        $this->reloadConfig();
        if (!is_int($this->getConfig()->get("DataUpdateInterval"))){
          $this->getLogger()->critical("DataUpdateInterval is not integer on config.yml");
          $this->getServer()->getPluginManager()->disablePlugin($this);
          return;
        }
        $this->interval = $this->getConfig()->get("DataUpdateInterval") * 20;
        $this->getScheduler()->scheduleRepeatingTask(new RepeatUpdateData(), $this->interval);
    }

    public function onLoad(){
      self::$instance = $this;
    }

    public function UpdateData($data){
      $this->requestedData = $data;
      return;
    }

    public static function getInstance(){
      return self::$instance;
    }

    private function ResultForm($player, $CountryInformation) {
      $form = new CustomForm(function (\pocketmine\Player $player, $data) {return;});
      $form->setTitle("Coronavirus Result");
      foreach(explode('\n',$CountryInformation) as $line){
          $form->addLabel($line);
      }
      $player->sendForm($form); 
    }


    private function covidUI($player) {
      $form = new CustomForm(function (\pocketmine\Player $player, $data){
          if(!isset($data[0])){return $player->sendMessage("§6CovidUI §d> §cUnknown Country.");}
          foreach($this->requestedData as $val){
              if (strtolower($data[0]) != strtolower($val["country"])){ continue; }
              $this->ResultForm($player, '§g Country: '.$val["country"].'\n§a Cases: '.$val["cases"].'\n§a Today Cases: '.$val["todayCases"].'\n§c Deaths: '.$val["deaths"].'\n§c Today Deaths: '.$val["todayDeaths"].'\n§b Recovered: '.$val["recovered"].'\n§b Today Recoveries: '.$val["todayRecovered"].'\n§a Active: '.$val["active"].'\n§c Critical: '.$val["critical"].'\n§a Cases Per One Million: '.$val["casesPerOneMillion"].'\n§c Deaths Per One Million: '.$val["deathsPerOneMillion"].'\n§a Tests: '.$val["tests"].'\n§a Tests Per One Million: '.$val["testsPerOneMillion"].'\n§a Population: '.$val["population"].'\n§a Continent: '.$val["continent"].'\n§a One Case Per People: '.$val["oneCasePerPeople"].'\n§c One Death Per People: '.$val["oneDeathPerPeople"].'\n§a One Test Per People: '.$val["oneTestPerPeople"].'\n§a Active Per One Million: '.$val["activePerOneMillion"].'\n§b Recovered Per One Million: '.$val["recoveredPerOneMillion"].'\n§c Critical Per One Million: '.$val["criticalPerOneMillion"]);
              return;
          }
          return $player->sendMessage("§6CovidUI §d> §cUnknown Country or Something went wrong.");
      });
      $form->setTitle("§6CovidUI");
      $form->addInput("§bCountry Name:", "Country", "");
      $player->sendForm($form);
    }


    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool{
      switch (strtolower($cmd->getName())) {
        case "covidui":
          if(!$player instanceof Player){ $player->sendMessage("§6CovidUI §d> §cThis command can only be ran ingame.");return true;}
          $player->sendMessage("§6CovidUI §d> §cLoading UI");
          $this->covidUI($player);
      }
      return true;
    }
}
