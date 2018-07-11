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
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0.0
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\virtualchest\command;

use kim\present\virtualchest\VirtualChest;
use pocketmine\command\{
	Command, CommandExecutor, CommandSender, ConsoleCommandSender, PluginCommand
};

class PoolCommand extends PluginCommand implements CommandExecutor{
	/** @var string */
	public $uname;

	/** @var Subcommand[] */
	protected $subCommands = [];

	/**
	 * @param VirtualChest $owner
	 * @param string       $name
	 * @param Subcommand[] $subCommands
	 */
	public function __construct(VirtualChest $owner, string $name, Subcommand ...$subCommands){
		parent::__construct($owner->getLanguage()->translateString("commands.{$name}"), $owner);
		$this->setExecutor($this);

		$this->uname = $name;
		$this->setPermission("{$name}.cmd");

		$this->description = $owner->getLanguage()->translateString("commands.{$this->uname}.description");
		$this->usageMessage = $this->getUsage(new ConsoleCommandSender());
		$aliases = $owner->getLanguage()->getArray("commands.{$this->uname}.aliases");
		if(is_array($aliases)){
			$this->setAliases($aliases);
		}

		$this->subCommands = $subCommands;
	}

	/**
	 * @param CommandSender|null $sender
	 *
	 * @return string
	 */
	public function getUsage(CommandSender $sender = null) : string{
		if($sender === null){
			return $this->usageMessage;
		}else{
			$subCommands = [];
			foreach($this->subCommands as $key => $subCommand){
				if($subCommand->checkPermission($sender)){
					$subCommands[] = $subCommand->getLabel();
				}
			}
			/** @var VirtualChest $plugin */
			$plugin = $this->getPlugin();
			$lang = $plugin->getLanguage();
			return $lang->translateString("commands.{$this->uname}.usage", [implode($lang->translateString("commands.{$this->uname}.usage.separator"), $subCommands)]);
		}
	}

	/**
	 * @param CommandSender $sender
	 * @param Command       $command
	 * @param string        $label
	 * @param string[]      $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(isset($args[0])){
			$label = array_shift($args);
			foreach($this->subCommands as $key => $value){
				if($value->checkLabel($label)){
					$value->execute($sender, $args);
					return true;
				}
			}
		}
		$sender->sendMessage($this->getPlugin()->getServer()->getLanguage()->translateString("commands.generic.usage", [$this->getUsage($sender)]));
		return true;
	}

	/**
	 * @return Subcommand[]
	 */
	public function getSubCommands() : array{
		return $this->subCommands;
	}

	/**
	 * @param Subcommand[] $subCommands
	 */
	public function setSubCommands(Subcommand ...$subCommands) : void{
		$this->subCommands = $subCommands;
	}

	/**
	 * @param Subcommand::class $subCommandClass
	 */
	public function createSubCommand($subCommandClass) : void{
		$this->subCommands[] = new $subCommandClass($this);
	}
}