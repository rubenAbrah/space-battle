<?php

namespace Tests\Command;

use App\Command\CommandInterface;
use App\Command\LogExceptionCommand;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LogExceptionCommandTest extends TestCase
{
    public function testExecuteLogsError(): void
    {
        $command = $this->createMock(CommandInterface::class);
        $exception = new RuntimeException('Test error');

        $logCommand = new LogExceptionCommand($command, $exception);

        ob_start();
        $logCommand->execute();
        $output = ob_get_clean();

        $this->assertStringContainsString(
            "Command " . get_class($command),
            $output
        );
        $this->assertStringContainsString(
            "failed with exception: Test error",
            $output
        );
    }
}