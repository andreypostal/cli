<?php
namespace Andrey\Cli\Examples\ParamsValidation;

use Andrey\Cli\Types\ConsoleLevel;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Utils\Output;

class Service
{
    use Output;

    public function process(Context $context): void
    {
        $thisExists = $context->get('required');
        self::console("Check it out: >> {$thisExists} <<", ConsoleLevel::SUCCESS);

        $thisMayNot = $context->get('optional');
        if ($thisMayNot === null) {
            self::console("OH NO >.<", ConsoleLevel::WARNING);
        } else {
            self::console("Oh, here he is >> {$thisMayNot} <<", ConsoleLevel::INFO);
        }
    }
}
