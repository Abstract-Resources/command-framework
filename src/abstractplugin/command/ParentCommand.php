<?php

declare(strict_types=1);

namespace abstractplugin\command;

use pocketmine\command\CommandSender;

interface ParentCommand {

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string|null
     */
    public function getPermission(): ?string;

    /**
     * @return array
     */
    public function getAliases(): array;

    /**
     * @param CommandSender $sender
     * @param string        $commandLabel
     * @param array         $args
     */
    public function onConsoleExecute(CommandSender $sender, string $commandLabel, array $args): void;
}