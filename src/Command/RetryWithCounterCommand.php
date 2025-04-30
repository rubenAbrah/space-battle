<?php

namespace App\Command;

class RetryWithCounterCommand implements CommandInterface {
    private CommandInterface $originalCommand;
    private int $attempts;

    public function __construct(CommandInterface $originalCommand, int $attempts = 0) {
        $this->originalCommand = $originalCommand;
        $this->attempts = $attempts;
    }

    public function execute(): void {
        $this->originalCommand->execute();
    }

    public function getOriginalCommand(): CommandInterface {
        return $this->originalCommand;
    }

    public function getAttempts(): int {
        return $this->attempts;
    }
}