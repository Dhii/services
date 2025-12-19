<?php

namespace Dhii\Services\Tests\Helpers;

class ClassBuilder
{
    /** @var ?class-string  */
    protected ?string $extends = null;
    /** @var array<interface-string> */
    protected array $implements = [];
    /** @var array<trait-string>  */
    protected array $uses = [];

    /**
     * @param class-string $classNamePrefix
     */
    public function __construct(
        protected string $classNamePrefix = 'Class_',
        protected bool $isAutoload = false,
    ) {
    }

    public function createClass(): string
    {
        $className = $this->createUniqueClassName($this->extends ?? $this->classNamePrefix);
        $classCode = $this->buildClassCode($className, $this->extends, $this->implements, $this->uses);
        eval($classCode);

        return $className;
    }

    public function __toString(): string
    {
        return $this->createClass();
    }

    /**
     * @param ?class-string $extends
     */
    public function withExtends(?string $extends = null): static
    {
        $that = clone $this;
        $that->extends = $extends;

        return $that;
    }

    /**
     * @param array<interface-string> $implements
     */
    public function withImplements(array $implements = []): static
    {
        $that = clone $this;
        $that->implements = $implements;

        return $that;
    }

    /**
     * @param array<trait-string> $uses
     */
    public function withUses(array $uses = []): static
    {
        $that = clone $this;
        $that->uses = $uses;

        return $that;
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

    /**
     * Creates a unique classname.
     */
    protected function createUniqueClassName(string $prefix = '', bool $isAutoload = false): string
    {
        $prefix = trim($prefix);
        $prefix = empty($prefix) ? 'Class_' : $prefix;

        $nameExists = fn(string $name) => class_exists($name, $isAutoload)
            || interface_exists($name, $isAutoload)
            || trait_exists($name, $isAutoload)
            || enum_exists($name, $isAutoload);

        do {
            $className = uniqid($prefix);
        } while ($nameExists($className));

        return $className;
    }
}
