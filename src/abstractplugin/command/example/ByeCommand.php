<?php

declare(strict_types=1);

namespace abstractplugin\command\example;

use abstractplugin\command\BaseCommand;

final class ByeCommand extends BaseCommand {

    public function __construct() {
        parent::__construct('bye');

        $this->registerParent(
            new HelloPlayerArgument('hello')
        );

        // TODO: Result is /bye hello print a message of {@link HelloPlayerArgument}
    }
}