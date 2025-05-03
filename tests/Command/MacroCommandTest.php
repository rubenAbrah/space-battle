<?php
namespace Tests\Command;

use App\Command\CommandInterface;
use App\Command\MacroCommand;
use App\Command\CommandException;
use PHPUnit\Framework\TestCase;

class MacroCommandTest extends TestCase
{
    public function testExecuteAllCommandsSuccessfully()
    {
        $command1 = $this->createMock(CommandInterface::class);
        $command1->expects($this->once())->method('execute');
        
        $command2 = $this->createMock(CommandInterface::class);
        $command2->expects($this->once())->method('execute');
        
        $macro = new MacroCommand([$command1, $command2]);
        $macro->execute();
    }
    
    public function testStopsOnFirstException()
    {
        $this->expectException(CommandException::class);
        
        $command1 = $this->createMock(CommandInterface::class);
        $command1->expects($this->once())
                 ->method('execute')
                 ->willThrowException(new CommandException());
        
        $command2 = $this->createMock(CommandInterface::class);
        $command2->expects($this->never())->method('execute');
        
        $macro = new MacroCommand([$command1, $command2]);
        $macro->execute();
    }
}