<?php

namespace Dhii\Services\Tests\Helpers;

class ClassBuilder
{
    /**
     * @param class-string $classNamePrefix
     */
    public function __construct(protected string $classNamePrefix = 'Class_')
    {
    }

    /**
     * @param class-string|null $extends
     * @param array<interface-string> $implements
     * @param array<trait-string> $uses
     */
    public function createClass(?string $extends = null, array $implements = [], array $uses = []): string
    {
        $className = $this->createUniqueClassName();
        $classCode = $this->buildClassCode($className, $extends, $implements, $uses);
        eval($classCode);

        return $className;
    }

    /**
     * @param class-string $className
     * @param class-string|null $extends
     * @param array<interface-string $implements
     * @param array<trait-string> $uses
     */
    protected function buildClassCode(
        string $className,
        ?string $extends = null,
        array $implements = [],
        array $uses = [],
    ) {
        $extendsClause = !is_null($extends)
            ? "extends $extends"
            : '';
        $implementsClause = count($implements)
            ? sprintf('implements %1$s', implode(', ', $implements))
            : '';
        $useClause = implode(' ', array_map(fn(string $fqcn) => "use $fqcn;", $uses));

        return "class $className $extendsClause $implementsClause { $useClause }";
    }

    protected function createUniqueClassName(): string
    {
        $nameExists = fn(string $name) => class_exists($name)
            || interface_exists($name)
            || trait_exists($name)
            || enum_exists($name);

        do {
            $className = uniqid($this->classNamePrefix);
        } while ($nameExists($className));

        return $className;
    }
}
