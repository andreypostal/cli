<?php
namespace Andrey\Cli\Examples\BasicService;

use Andrey\Cli\Types\Context;
use Andrey\Cli\Utils\Output;

class Service
{
    use Output;

    public function process(Context $context): void
    {
        $arg = $context->get('arg');
        self::console("Look at this nice param --> {$arg} <--");
    }
}
