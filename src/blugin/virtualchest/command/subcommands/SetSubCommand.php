<?php

namespace blugin\virtualchest\command\subcommands;

use pocketmine\Server;
use pocketmine\command\CommandSender;
use blugin\virtualchest\VirtualChest;
use blugin\virtualchest\command\{
  PoolCommand, SubCommand
};
use blugin\virtualchest\container\VirtualChestContainer;
use blugin\virtualchest\util\{
  Translation, Utils
};

class SetSubCommand extends SubCommand{

    public function __construct(PoolCommand $owner){
        parent::__construct($owner, 'set');
    }

    /**
     * @param CommandSender $sender
     * @param String[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, array $args) : bool{
        if (isset($args[1])) {
            $container = VirtualChestContainer::getContainer($playerName = strtolower($args[0]), true);
            if ($container === null) {
                $player = Server::getInstance()->getPlayer($playerName);
                if ($player !== null) {
                    $container = VirtualChestContainer::getContainer($playerName = $player->getLowerCaseName(), true);
                }
            }
            if ($container === null) {
                $sender->sendMessage(VirtualChest::$prefix . Translation::translate('command-generic-failure@invalid-player', $args[0]));
            } else {
                $count = Utils::toInt($args[1], null, function (int $i){
                    return $i >= 0;
                });
                if ($count === null) {
                    $sender->sendMessage(VirtualChest::$prefix . Translation::translate('command-generic-failure@invalid', $args[1]));
                } else {
                    $container->setCount($count);
                    $sender->sendMessage(VirtualChest::$prefix . $this->translate('success', $playerName, $count));
                }
            }
            return true;
        }
        return false;
    }
}