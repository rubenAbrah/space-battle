<?php
namespace App\Command;

use Throwable;

interface ExceptionHandlerInterface {
    public function handle(CommandInterface $command, Throwable $exception): void;
}