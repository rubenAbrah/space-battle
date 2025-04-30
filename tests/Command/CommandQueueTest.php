<?php

namespace Tests\Command;

use App\Command\CommandInterface;
use App\Command\CommandQueue;
use App\Command\ExceptionHandlerInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class CommandQueueTest extends TestCase {
    private CommandQueue $queue;
    
    protected function setUp(): void {
        $this->queue = new CommandQueue();
    }
    
    public function testProcessExecutesCommands(): void {
        $command = $this->createMock(CommandInterface::class);
        $command->expects($this->once())
               ->method('execute');
        
        $this->queue->addCommand($command);
        $this->queue->process();
    }
    
    public function testProcessHandlesExceptions(): void {
        $handler = $this->createMock(ExceptionHandlerInterface::class);
        $handler->expects($this->once())
               ->method('handle');
        
        $this->queue->setExceptionHandler(RuntimeException::class, $handler);
        
        $failingCommand = new class implements CommandInterface {
            public function execute(): void {
                throw new RuntimeException('Test');
            }
        };
        
        $this->queue->addCommand($failingCommand);
        $this->queue->process();
    }
    
    public function testDefaultHandlerCatchesUnhandledExceptions(): void {
        $handler = $this->createMock(ExceptionHandlerInterface::class);
        $handler->expects($this->once())
               ->method('handle');
        
        $this->queue->setDefaultExceptionHandler($handler);
        
        $failingCommand = new class implements CommandInterface {
            public function execute(): void {
                throw new \Exception('Test');
            }
        };
        
        $this->queue->addCommand($failingCommand);
        $this->queue->process();
    }
    
    public function testUnhandledExceptionThrows(): void {
        $this->expectException(RuntimeException::class);
        
        $failingCommand = new class implements CommandInterface {
            public function execute(): void {
                throw new RuntimeException('Test');
            }
        };
        
        $this->queue->addCommand($failingCommand);
        $this->queue->process();
    }
}