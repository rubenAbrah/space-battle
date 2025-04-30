<?php

namespace App\Command;

class LogExceptionCommand implements CommandInterface {
    private CommandInterface $failedCommand;
    private \Throwable $exception;

    public function __construct(CommandInterface $failedCommand, \Throwable $exception) {
        $this->failedCommand = $failedCommand;
        $this->exception = $exception;
    }

    public function execute(): void {
        echo sprintf(
            "Command %s failed with exception: %s\n",
            get_class($this->failedCommand),
            $this->exception->getMessage()
        );
    }
}