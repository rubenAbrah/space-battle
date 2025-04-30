<?php

namespace App\Command;

class CommandQueue implements \Countable {
    private array $queue = [];
    private array $exceptionHandlers = [];
    private ?ExceptionHandlerInterface $defaultHandler = null;

    public function addCommand(CommandInterface $command): void {
        $this->queue[] = $command;
    }

    public function setExceptionHandler(string $exceptionClass, ExceptionHandlerInterface $handler): void {
        $this->exceptionHandlers[$exceptionClass] = $handler;
    }

    public function setDefaultExceptionHandler(ExceptionHandlerInterface $handler): void {
        $this->defaultHandler = $handler;
    }

    public function process(): void {
        while (!empty($this->queue)) {
            $command = array_shift($this->queue);
            
            try {
                $command->execute();
            } catch (\Throwable $e) {
                $handler = $this->findHandler($e);
                
                if ($handler !== null) {
                    $handler->handle($command, $e);
                } elseif ($this->defaultHandler !== null) {
                    $this->defaultHandler->handle($command, $e);
                } else {
                    throw $e;
                }
            }
        }
    }

    private function findHandler(\Throwable $exception): ?ExceptionHandlerInterface {
        foreach ($this->exceptionHandlers as $exceptionClass => $handler) {
            if ($exception instanceof $exceptionClass) {
                return $handler;
            }
        }
        return null;
    }

    public function count(): int {
        return count($this->queue);
    }

    public function get(int $index): ?CommandInterface {
        return $this->queue[$index] ?? null;
    }
}