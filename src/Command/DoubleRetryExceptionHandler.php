<?php
namespace App\Command;

use Throwable;


class DoubleRetryExceptionHandler implements ExceptionHandlerInterface {
    private CommandQueue $queue;

    public function __construct(CommandQueue $queue) {
        $this->queue = $queue;
    }

    public function handle(CommandInterface $command, Throwable $exception): void {
        if ($command instanceof DoubleRetryCommand) {
            if ($command->getAttempts() < 2) {
                $this->queue->addCommand(new DoubleRetryCommand(
                    $command->getOriginalCommand(),
                    $command->getAttempts() + 1
                ));
            } else {
                $this->queue->addCommand(new LogExceptionCommand($command, $exception));
            }
        } else {
            $this->queue->addCommand(new DoubleRetryCommand($command, 1));
        }
    }
}