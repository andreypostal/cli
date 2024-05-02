<?php
namespace Andrey\Cli\Types;

readonly class Param
{
    public mixed $value;

    public function __construct(
        public string $key,
        public string $flag,
        public bool $required = false,
        public ?string $defaultValue = null,
        public ?string $helpText = null,
        public bool $isBool = false,
    ) { }

    public function withValue(mixed $value): self
    {
        $clone = clone $this;
        $clone->value = $value;
        return $clone;
    }
}
