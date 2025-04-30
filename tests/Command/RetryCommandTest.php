<?php
namespace Tests\CommandInterface;

use App\Command\RetryCommand;
use PHPUnit\Framework\TestCase;
use App\Command\CommandInterface;

class RetryCommandTest extends TestCase {
    public function testExecuteCallsOriginalCommand(): void {
        $originalCommand = $this->createMock(CommandInterface::class);
        $originalCommand->expects($this->once())
                      ->method('execute');
        
        $retryCommand = new RetryCommand($originalCommand);
        $retryCommand->execute();
    }
}