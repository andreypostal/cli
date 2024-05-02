<?php
namespace Andrey\Cli\Examples\ProgressBar;

use Andrey\Cli\Components\ProgressBar;
use Andrey\Cli\Types\ConsoleLevel;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Utils\Output;

class Service
{
    use Output;
    use ProgressBar;

    public function process(Context $context): void
    {
        self::center('Check this really nice progress bar', ConsoleLevel::HIGHLIGHT);

        $totalItems = 742;

        $this->initProgressBar($totalItems);
        for ($i = 0; $i < $totalItems / 2; $i++) {
            $this->addProgress(2);
            usleep(1000);
        }
        $this->endProgressBar();

        self::center('And we are done :)', ConsoleLevel::INFO);
        self::center('Thanks for watching!');
    }
}
