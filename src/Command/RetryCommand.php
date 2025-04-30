<?php

namespace App\Command;

class RetryCommand implements CommandInterface {
    private CommandInterface $originalCommand;

    public function __construct(CommandInterface $originalCommand) {
        $this->originalCommand = $originalCommand;
    }

    public function execute(): void {
        $this->originalCommand->execute();
    }
}