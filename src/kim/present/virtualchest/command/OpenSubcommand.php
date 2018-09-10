<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\virtualchest\command;

use kim\present\virtualchest\container\VirtualChestContainer;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class OpenSubcommand extends Subcommand{
	public const LABEL = "open";

	/**
	 * @param CommandSender $sender
	 * @param String[]      $args = []
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args = []) : bool{
		if($sender instanceof Player){
			$container = VirtualChestContainer::getContainer($playerName = $sender->getLowerCaseName(), true);
			if($container === null){
				$defaultCount = $this->plugin->getDefaultCount();
				if($defaultCount < 1){
					$sender->sendMessage($this->plugin->getLanguage()->translate("commands.virtualchest.open.failure.none"));
					return true;
				}else{
					$container = new VirtualChestContainer($playerName, $defaultCount);
					VirtualChestContainer::setContainer($playerName, $container);
				}
			}
			$number = isset($args[0]) ? strtolower($args[0]) : 1;
			$count = $container->getCount();
			if(!is_numeric($number) || $number > $count){
				$sender->sendMessage($this->plugin->getLanguage()->translate("commands.virtualchest.open.failure.invalid", [$number]));
				$sender->sendMessage($this->plugin->getLanguage()->translate("commands.virtualchest.open.count", [(string) $count]));
			}else{
				$sender->addWindow($container->getChest($number - 1));
			}
		}else{
			$sender->sendMessage($this->plugin->getLanguage()->translate("commands.generic.onlyPlayer"));
		}
		return true;
	}
}