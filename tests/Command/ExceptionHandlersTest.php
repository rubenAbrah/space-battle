<?php
namespace Tests\Command;

use App\Command\CommandInterface;
use RuntimeException;
use App\Command\CommandQueue;
use App\Command\RetryCommand;
use PHPUnit\Framework\TestCase;
use App\Command\DoubleRetryCommand;
use App\Command\LogExceptionCommand;
use App\Command\RetryExceptionHandler;
use App\Command\LoggingExceptionHandler;
use App\Command\RetryWithCounterCommand;
use App\Command\CompositeExceptionHandler;
use App\Command\DoubleRetryExceptionHandler;

class ExceptionHandlersTest extends TestCase {
    private CommandQueue $queue;
    
    protected function setUp(): void {
        $this->queue = new CommandQueue();
    }
    
    public function testLoggingHandlerAddsLogCommand(): void {
        $handler = new LoggingExceptionHandler($this->queue);
        $command = $this->createMock(CommandInterface::class);
        $exception = new RuntimeException('Test');
        
        $handler->handle($command, $exception);
        
        $this->assertCount(1, $this->queue);
        $this->assertInstanceOf(LogExceptionCommand::class, $this->queue->get(0));
    }
    
    public function testRetryHandlerAddsRetryCommand(): void {
        $handler = new RetryExceptionHandler($this->queue);
        $command = $this->createMock(CommandInterface::class);
        $exception = new RuntimeException('Test');
        
        $handler->handle($command, $exception);
        
        $this->assertCount(1, $this->queue);
        $this->assertInstanceOf(RetryCommand::class, $this->queue->get(0));
    }
    
    public function testCompositeHandlerFirstAttemptRetries(): void {
        $handler = new CompositeExceptionHandler($this->queue);
        $command = $this->createMock(CommandInterface::class);
        $exception = new RuntimeException('Test');
        
        $handler->handle($command, $exception);
        
        $this->assertCount(1, $this->queue);
        $queuedCommand = $this->queue->get(0);
        $this->assertInstanceOf(RetryWithCounterCommand::class, $queuedCommand);
        $this->assertEquals(1, $queuedCommand->getAttempts());
    }
    
    public function testCompositeHandlerSecondAttemptLogs(): void {
        $handler = new CompositeExceptionHandler($this->queue);
        $originalCommand = $this->createMock(CommandInterface::class);
        $command = new RetryWithCounterCommand($originalCommand, 1);
        $exception = new RuntimeException('Test');
        
        $handler->handle($command, $exception);
        
        $this->assertCount(1, $this->queue);
        $this->assertInstanceOf(LogExceptionCommand::class, $this->queue->get(0));
    }
    
    public function testDoubleRetryHandlerFirstAttemptRetries(): void {
        $handler = new DoubleRetryExceptionHandler($this->queue);
        $command = $this->createMock(CommandInterface::class);
        $exception = new RuntimeException('Test');
        
        $handler->handle($command, $exception);
        
        $this->assertCount(1, $this->queue);
        $queuedCommand = $this->queue->get(0);
        $this->assertInstanceOf(DoubleRetryCommand::class, $queuedCommand);
        $this->assertEquals(1, $queuedCommand->getAttempts());
    }
    
    public function testDoubleRetryHandlerThirdAttemptLogs(): void {
        $handler = new DoubleRetryExceptionHandler($this->queue);
        $originalCommand = $this->createMock(CommandInterface::class);
        $command = new DoubleRetryCommand($originalCommand, 2);
        $exception = new RuntimeException('Test');
        
        $handler->handle($command, $exception);
        
        $this->assertCount(1, $this->queue);
        $this->assertInstanceOf(LogExceptionCommand::class, $this->queue->get(0));
    }
}