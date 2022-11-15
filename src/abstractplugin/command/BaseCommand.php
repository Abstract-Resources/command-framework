<?php

declare(strict_types=1);

namespace abstractplugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use function array_shift;
use function in_array;
use function is_file;
use function strtolower;

abstract class BaseCommand extends Command implements ParentCommand {

    /** @var array<string, ParentCommand> */
    private array $parents = [];

    /**
     * @param ParentCommand ...$parents
     */
    protected function registerParent(ParentCommand ...$parents): void {
        foreach ($parents as $parent) {
            $this->parents[strtolower($parent->getName())] = $parent;
        }
    }

    /**
     * @param string $argumentName
     *
     * @return ParentCommand|null
     */
    private function getParent(string $argumentName): ?ParentCommand {
        if (($issetParent = $this->parents[strtolower($argumentName)] ?? null) !== null) {
            return $issetParent;
        }

        foreach ($this->parents as $parent) {
            if (!in_array(strtolower($argumentName), $parent->getAliases(), true)) {
                continue;
            }

            return $parent;
        }

        return null;
    }

    /**
     * @param CommandSender $sender
     * @param string        $commandLabel
     * @param array         $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (($argumentName = array_shift($args)) === null) {
            $sender->sendMessage(TextFormat::RED . 'Usage: \'/' . $commandLabel . ' help\'');

            return;
        }

        if (($parent = $this->getParent($argumentName)) === null) {
            $sender->sendMessage(TextFormat::RED . 'Usage: \'/' . $commandLabel . ' help\'');

            return;
        }

        if (($permission = $parent->getPermission()) !== null && !$sender->hasPermission($permission)) {
            $sender->sendMessage(TextFormat::RED . 'You don\'t have permissions to use this command.');

            return;
        }

        $parent->onConsoleExecute($sender, $commandLabel, $args);
    }

    /**
     * @param CommandSender $sender
     * @param string        $commandLabel
     * @param array         $args
     */
    public function onConsoleExecute(CommandSender $sender, string $commandLabel, array $args): void {}

    /**
     * @param PluginBase $plugin
     */
    public static function init(PluginBase $plugin): void {
        if (!is_file($bootstrap = 'phar://' . Server::getInstance()->getPluginPath() . $plugin->getName() . '.phar/vendor/autoload.php')) {
            $plugin->getLogger()->error('Composer autoloader not found at ' . $bootstrap);
            $plugin->getLogger()->warning('Please install/update Composer dependencies or use provided build.');

            exit(1);
        }

        require_once($bootstrap);
    }
}