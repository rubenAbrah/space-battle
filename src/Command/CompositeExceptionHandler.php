<?php

namespace App\Command;

use Throwable;

class CompositeExceptionHandler implements ExceptionHandlerInterface
{
    private CommandQueue $queue;

    public function __construct(CommandQueue $queue)
    {
        $this->queue = $queue;
    }

    public function handle(CommandInterface $command, Throwable $exception): void
    {
        if ($command instanceof RetryWithCounterCommand) {
            if ($command->getAttempts() < 1) {
                $this->queue->addCommand(new RetryWithCounterCommand(
                    $command->getOriginalCommand(),
                    $command->getAttempts() + 1
                ));
            } else {
                $this->queue->addCommand(new LogExceptionCommand($command, $exception));
            }
        } else {
            $this->queue->addCommand(new RetryWithCounterCommand($command, 1));
        }
    }
}