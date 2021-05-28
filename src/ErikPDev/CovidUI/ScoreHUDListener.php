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

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\event\ServerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use ErikPDev\CovidUI\Main;
class ScoreHUDListener implements Listener{

    private $plugin;


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onTagResolve(TagsResolveEvent $event){
        $player = $event->getPlayer();
        $tag = $event->getTag();
    
        switch($tag->getName()){
            case "covidui.cases":
                $tag->setValue((string)$this->plugin->WorldWideData["cases"]);
            break;
            case "covidui.todayCases":
                $tag->setValue((string)$this->plugin->WorldWideData["todayCases"]);
            break;
            case "covidui.deaths":
                $tag->setValue((string)$this->plugin->WorldWideData["deaths"]);
            break;
            case "covidui.todayDeaths":
                $tag->setValue((string)$this->plugin->WorldWideData["todayDeaths"]);
            break;
            case "covidui.recovered":
                $tag->setValue((string)$this->plugin->WorldWideData["recovered"]);
            break;
            case "covidui.todayRecovered":
                $tag->setValue((string)$this->plugin->WorldWideData["todayRecovered"]);
            break;
            case "covidui.active":
                $tag->setValue((string)$this->plugin->WorldWideData["active"]);
            break;
            case "covidui.critical":
                $tag->setValue((string)$this->plugin->WorldWideData["critical"]);
            break;
            case "covidui.casesPerOneMillion":
                $tag->setValue((string)$this->plugin->WorldWideData["casesPerOneMillion"]);
            break;
            case "covidui.deathsPerOneMillion":
                $tag->setValue((string)$this->plugin->WorldWideData["deathsPerOneMillion"]);
            break;
            case "covidui.tests":
                $tag->setValue((string)$this->plugin->WorldWideData["tests"]);
            break;
            case "covidui.testsPerOneMillion":
                $tag->setValue((string)$this->plugin->WorldWideData["testsPerOneMillion"]);
            break;
            case "covidui.population":
                $tag->setValue((string)$this->plugin->WorldWideData["population"]);
            break;
            case "covidui.oneCasePerPeople":
                $tag->setValue((string)$this->plugin->WorldWideData["oneCasePerPeople"]);
            break;
            case "covidui.oneDeathPerPeople":
                $tag->setValue((string)$this->plugin->WorldWideData["oneDeathPerPeople"]);
            break;
            case "covidui.oneTestPerPeople":
                $tag->setValue((string)$this->plugin->WorldWideData["oneTestPerPeople"]);
            break;
            case "covidui.activePerOneMillion":
                $tag->setValue((string)$this->plugin->WorldWideData["activePerOneMillion"]);
            break;
            case "covidui.recoveredPerOneMillion":
                $tag->setValue((string)$this->plugin->WorldWideData["recoveredPerOneMillion"]);
            break;
            case "covidui.criticalPerOneMillion":
                $tag->setValue((string)$this->plugin->WorldWideData["criticalPerOneMillion"]);
            break;
            case "covidui.affectedCountries":
                $tag->setValue((string)$this->plugin->WorldWideData["affectedCountries"]);
            break;
        }
    }
    public function update(){
        foreach ($this->plugin->WorldWideData as $key => $value) {
            $ev = new ServerTagUpdateEvent(new ScoreTag(
                "covidui".$key,
                strval((string)$value))
            );
            $ev->call();
        }
    }
}