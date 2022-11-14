<?php

declare(strict_types=1);

namespace abstractplugin\command\example;

use abstractplugin\command\Argument;
use abstractplugin\command\PlayerArgumentTrait;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class HelloPlayerArgument extends Argument {
    use PlayerArgumentTrait;

    /**
     * @param Player $player
     * @param string $label
     * @param array  $args
     */
    public function onPlayerExecute(Player $player, string $label, array $args): void {
        $player->sendMessage(TextFormat::RED . 'This command was executed by a player');
    }
}