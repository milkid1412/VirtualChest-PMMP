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
use pocketmine\Server;

class SetSubcommand extends Subcommand{
	public const LABEL = "set";

	/**
	 * @param CommandSender $sender
	 * @param String[]      $args = []
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args = []) : bool{
		if(isset($args[1])){
			$container = VirtualChestContainer::getContainer($playerName = strtolower($args[0]), true);
			if($container === null){
				$player = Server::getInstance()->getPlayer($playerName);
				if($player !== null){
					$container = VirtualChestContainer::getContainer($playerName = $player->getLowerCaseName(), true);
					if($container === null){
						$container = new VirtualChestContainer($playerName, $this->plugin->getDefaultCount());
						VirtualChestContainer::setContainer($playerName, $container);
					}
				}
			}
			if($container === null){
				$sender->sendMessage($this->plugin->getLanguage()->translate("commands.generic.player.notFound", [$args[0]]));
			}elseif(!is_numeric($args[1])){
				$sender->sendMessage($this->plugin->getLanguage()->translate("commands.generic.num.notNumber", [$args[1]]));
			}else{
				$count = (int) $args[1];
				if($count < 0){
					$sender->sendMessage($this->plugin->getLanguage()->translate("commands.generic.num.tooSmall", [$args[1], "0"]));
				}else{
					$container->setCount($count);
					$sender->sendMessage($this->plugin->getLanguage()->translate("commands.virtualchest.set.success", [$playerName, (string) $count]));
				}
			}
			return true;
		}
		return false;
	}
}