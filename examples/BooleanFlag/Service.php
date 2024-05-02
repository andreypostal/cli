<?php
namespace Andrey\Cli\Examples\BooleanFlag;

use Andrey\Cli\Types\ConsoleLevel;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Utils\Output;

class Service
{
    use Output;

    public function process(Context $context): void
    {
        $watch = $context->get('watch');

        self::console('Should we watch it?');

        self::console(
            $watch ? 'Yep' : 'Nope',
            $watch ? ConsoleLevel::SUCCESS : ConsoleLevel::ERROR,
        );
    }
}
