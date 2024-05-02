<?php
namespace Andrey\Cli\Examples\InfiniteProgressBar;

use Andrey\Cli\Components\InfiniteProgressBar;
use Andrey\Cli\Types\ConsoleLevel;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Utils\Output;

class Service
{
    use Output;
    use InfiniteProgressBar;

    public function process(Context $context): void
    {
        self::center('Check this really nice infinite progress bar', ConsoleLevel::HIGHLIGHT);

        $this->initInfiniteBar();
        for ($i = 0; $i < 10; $i++) {
            $this->loopInfiniteBar();
        }
        $this->endInfiniteBar();

        self::center('And we are done :)', ConsoleLevel::INFO);
        self::center('Thanks for watching!');
    }
}
