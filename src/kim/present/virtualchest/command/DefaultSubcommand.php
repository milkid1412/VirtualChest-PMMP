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

use pocketmine\command\CommandSender;

class DefaultSubcommand extends Subcommand{
	public const LABEL = "default";

	/**
	 * @param CommandSender $sender
	 * @param String[]      $args = []
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args = []) : bool{
		if(isset($args[0])){
			if(!is_numeric($args[0])){
				$sender->sendMessage($this->plugin->getLanguage()->translate("commands.generic.num.notNumber", [$args[0]]));
			}else{
				$count = (int) $args[0];
				if($count < 0){
					$sender->sendMessage($this->plugin->getLanguage()->translate("commands.generic.num.tooSmall", [$args[0], "0"]));
				}else{
					$this->plugin->setDefaultCount($count);
					$sender->sendMessage($this->plugin->getLanguage()->translate("commands.virtualchest.default.success", [(string) $count]));
				}
			}
			return true;
		}
		return false;
	}
}