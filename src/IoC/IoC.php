<?php
namespace App\IoC;

class IoC
{
    private static array $scopes = ['default' => []];
    private static string $currentScope = 'default';

    public static function Resolve(string $key, ...$args)
    {
        // Handle scope commands first
        if ($key === 'Scopes.New') {
            $scopeId = $args[0];
            $parentScope = $args[1] ?? null;
            
            if (isset(self::$scopes[$scopeId])) {
                throw new \Exception("Scope already exists: $scopeId");
            }
            
            self::$scopes[$scopeId] = $parentScope 
                ? array_merge([], self::$scopes[$parentScope])
                : [];
            return;
        }
        
        if ($key === 'Scopes.Current') {
            $scopeId = $args[0];
            if (!isset(self::$scopes[$scopeId])) {
                throw new \Exception("Scope not found: $scopeId");
            }
            self::$currentScope = $scopeId;
            return;
        }
        
        // Handle registration
        if ($key === 'IoC.Register') {
            $dependencyKey = $args[0];
            $factory = $args[1];
            $isSingleton = $args[2] ?? false;
            
            if ($isSingleton) {
                $instance = null;
                self::$scopes[self::$currentScope][$dependencyKey] = function (...$args) use ($factory, &$instance) {
                    if ($instance === null) {
                        $instance = $factory(...$args);
                    }
                    return $instance;
                };
            } else {
                self::$scopes[self::$currentScope][$dependencyKey] = function (...$args) use ($factory) {
                    return $factory(...$args);
                };
            }
            return;
        }
        
        // Resolve dependency
        if (!isset(self::$scopes[self::$currentScope][$key])) {
            throw new \Exception("Dependency not registered: $key");
        }
        
        $dependency = self::$scopes[self::$currentScope][$key];
        return $dependency(...$args);
    }
}