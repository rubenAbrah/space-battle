<?php
namespace App\Command;

use Throwable;

class LoggingExceptionHandler implements ExceptionHandlerInterface {
    private CommandQueue $queue;

    public function __construct(CommandQueue $queue) {
        $this->queue = $queue;
    }

    public function handle(CommandInterface $command, Throwable $exception): void {
        $this->queue->addCommand(new LogExceptionCommand($command, $exception));
    }
}