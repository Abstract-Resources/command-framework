<?php

declare(strict_types=1);

namespace abstractplugin\command;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

trait PlayerArgumentTrait {

    /**
     * @param CommandSender $sender
     * @param string        $label
     * @param array         $args
     */
    public function onConsoleExecute(CommandSender $sender, string $label, array $args): void {
        if (!$this instanceof ParentCommand) return;

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . 'Run this command in-game');

            return;
        }

        $this->onPlayerExecute($sender, $label, $args);
    }

    /**
     * @param Player $sender
     * @param string $label
     * @param array  $args
     */
    abstract public function onPlayerExecute(Player $sender, string $label, array $args): void;
}