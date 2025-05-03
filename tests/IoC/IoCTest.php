<?php
// tests/IoC/IoCTest.php
namespace Tests\IoC;

use Exception;
use PHPUnit\Framework\TestCase;
use App\IoC\IoC;
use stdClass;

class IoCTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset to default scope before each test
        IoC::Resolve('Scopes.Current', 'default');
    }

    public function testCanRegisterAndResolveSimpleDependency()
    {
        IoC::Resolve('IoC.Register', 'test.service', function () {
            return new stdClass();
        });

        $instance = IoC::Resolve('test.service');
        $this->assertInstanceOf(stdClass::class, $instance);
    }

    public function testSingletonRegistrationReturnsSameInstance()
    {
        IoC::Resolve('IoC.Register', 'singleton.service', function () {
            return new stdClass();
        }, true);

        $instance1 = IoC::Resolve('singleton.service');
        $instance2 = IoC::Resolve('singleton.service');
        $this->assertSame($instance1, $instance2);
    }

    public function testCanCreateAndSwitchScopes()
    {
        // Create new scope
        IoC::Resolve('Scopes.New', 'test.scope');
        IoC::Resolve('Scopes.Current', 'test.scope');

        // Register in new scope
        IoC::Resolve('IoC.Register', 'scoped.service', function () {
            return new stdClass();
        });

        // Verify resolution in new scope
        $instance = IoC::Resolve('scoped.service');
        $this->assertInstanceOf(stdClass::class, $instance);

        // Switch back to default scope
        IoC::Resolve('Scopes.Current', 'default');

        // Verify service not available in default scope
        $this->expectException(\Exception::class);
        IoC::Resolve('scoped.service');
    }

    public function testNestedScopesInheritDependencies()
    {
        // Create parent scope
        IoC::Resolve('Scopes.New', 'parent.scope');

        // Register in parent scope
        IoC::Resolve('Scopes.Current', 'parent.scope');
        IoC::Resolve('IoC.Register', 'parent.service', function () {
            return new stdClass();
        });

        // Create child scope
        IoC::Resolve('Scopes.New', 'child.scope', 'parent.scope');
        IoC::Resolve('Scopes.Current', 'child.scope');

        // Verify can resolve parent service
        $instance = IoC::Resolve('parent.service');
        $this->assertInstanceOf(stdClass::class, $instance);

        // Register in child scope
        IoC::Resolve('IoC.Register', 'child.service', function () {
            return new stdClass();
        });

        // Switch back to parent scope
        IoC::Resolve('Scopes.Current', 'parent.scope');

        // Verify child service not available in parent
        $this->expectException(\Exception::class);
        IoC::Resolve('child.service');
    }

    public function testCanPassArgumentsToDependencyFactory()
    {
        IoC::Resolve('IoC.Register', 'service.with.args', function ($arg1, $arg2) {
            $obj = new stdClass();
            $obj->arg1 = $arg1;
            $obj->arg2 = $arg2;
            return $obj;
        });

        $instance = IoC::Resolve('service.with.args', 'value1', 42);
        $this->assertEquals('value1', $instance->arg1);
        $this->assertEquals(42, $instance->arg2);
    }

    public function testCannotCreateDuplicateScope()
    {
        IoC::Resolve('Scopes.New', 'duplicate.scope');
        $this->expectException(\Exception::class);
        IoC::Resolve('Scopes.New', 'duplicate.scope');
    }

    public function testThrowsExceptionForUnregisteredDependency()
    {
        $this->expectException(\Exception::class);
        IoC::Resolve('unregistered.service');
    }

    public function testThrowsExceptionForInvalidScope()
    {
        $this->expectException(\Exception::class);
        IoC::Resolve('Scopes.Current', 'nonexistent.scope');
    }
}