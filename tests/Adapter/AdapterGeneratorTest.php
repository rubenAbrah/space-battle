<?php
namespace Tests\Unit;

use App\IoC\IoC;
use PHPUnit\Framework\TestCase; 
use App\Movement\MovableInterface;
use App\Services\AdapterGenerator;

class AdapterGeneratorTest extends TestCase
{
    protected function tearDown(): void
    {
        // Очищаем IoC контейнер после каждого теста
        IoC::Resolve('Scopes.Current', 'default');
    }

    public function testGeneratesMovableAdapter()
    {
        $generator = new AdapterGenerator();
        $adapterClass = $generator->generate(MovableInterface::class);
        
        $this->assertTrue(class_exists($adapterClass));
        
        $mockObj = $this->createMock(\stdClass::class);
        $adapter = new $adapterClass($mockObj);
        
        $this->assertInstanceOf(MovableInterface::class, $adapter);
    }

    public function testAdapterMethods()
    {
        $generator = new AdapterGenerator();
        $adapterClass = $generator->generate(MovableInterface::class);
        
        $mockObj = $this->createMock(\stdClass::class);
        $adapter = new $adapterClass($mockObj);
        
        // Регистрируем зависимости в IoC
        IoC::Resolve('IoC.Register', MovableInterface::class . ':getPosition', function ($obj) {
            return [10, 20];
        });
        
        IoC::Resolve('IoC.Register', MovableInterface::class . ':setPosition', function ($obj, $position) {
            return new class {
                public function execute() {}
            };
        });
        
        // Test getPosition
        $this->assertEquals([10, 20], $adapter->getPosition());
        
        // Test setPosition (проверяем что не бросает исключение)
        $adapter->setPosition([30, 40]);
        $this->assertTrue(true); // Просто проверяем что выполнение дошло до этого места
    }
}