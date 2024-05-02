<?php
namespace Andrey\Cli\Types;

class Command
{
    public function __construct(
        public string $key,
        public string $description,
        /** @var array{instance:string|callable, entrypoint:string} */
        public array $service,
        /** @var Param[] */
        public array $params = [],
    ) { }
}
