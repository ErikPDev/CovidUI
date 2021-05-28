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

 // Feel free to use this in your plugins, but you should credit.
namespace ErikPDev\CovidUI;

class VersionManager{
    
    private $main;

    public function __construct(Main $main){
        $this->main = $main;
    }

    public function isLatest(string $pluginName,float $verison){ 
        $PluginVerison = floatval( $this->main->getServer()->getPluginManager()->getPlugin($pluginName)->getDescription()->getVersion() );
        if($PluginVerison >= $verison){
            return true;
        }
        return false;
    }
}