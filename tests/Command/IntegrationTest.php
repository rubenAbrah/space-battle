<?php

namespace Tests\Command;

use App\Command\CommandInterface;
use App\Command\CommandQueue;
use App\Command\DoubleRetryCommand;
use App\Command\DoubleRetryExceptionHandler;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class IntegrationTest extends TestCase {
    public function testCompleteFlowWithDoubleRetry(): void {
        $queue = new CommandQueue();
        $queue->setExceptionHandler(
            RuntimeException::class,
            new DoubleRetryExceptionHandler($queue)
        );
        
        $counter = 0;
        $command = new class($counter) implements CommandInterface {
            private $counter;
            
            public function __construct(&$counter) {
                $this->counter = &$counter;
            }
            
            public function execute(): void {
                $this->counter++;
                if ($this->counter < 3) {
                    throw new RuntimeException("Attempt {$this->counter}");
                }
            }
        };
        
        $queue->addCommand($command);
        $queue->process();
        
        $this->assertEquals(3, $counter);
    }
    
    public function testCompleteFlowWithLoggingAfterRetries(): void {
        $queue = new CommandQueue();
        $queue->setExceptionHandler(
            RuntimeException::class,
            new DoubleRetryExceptionHandler($queue)
        );
        
        $command = new class implements CommandInterface {
            public function execute(): void {
                throw new RuntimeException("Always fails");
            }
        };
        
        $expectedOutput = sprintf(
            "Command %s failed with exception: Always fails\n",
            DoubleRetryCommand::class
        );
        
        $this->expectOutputString($expectedOutput);
        
        $queue->addCommand($command);
        $queue->process();
    }
}