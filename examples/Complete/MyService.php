<?php

namespace Andrey\Cli\Examples\Complete;

use Random\RandomException;

use Andrey\Cli\Utils\Output;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Types\ConsoleLevel;
use Andrey\Cli\Components\ProgressBar;
use Andrey\Cli\Components\InfiniteProgressBar;

class MyService
{
    use Output;
    use ProgressBar;
    use InfiniteProgressBar;

    /**
     * @throws RandomException
     */
    public function oneHandler(Context $context): void
    {
        self::console('Just starting');

        // Recover the parameter by the key
        $user = $context->get('user');
        // We can retrieve by the flag as well we can choose one or another
        $password = $context->get('p');

        $success = ($user === 'jorginho' && $password === 'secure');

        if (!$success) {
            self::console('Wrong credentials!', ConsoleLevel::ERROR);
            self::console('The user is `jorginho` and the password is `secure`', ConsoleLevel::INFO);
            self::console('Please don\'t tell anyone');

            return;
        }

        self::console('Successfully logged in!', ConsoleLevel::SUCCESS);

        self::center("Welcome {$user}", ConsoleLevel::HIGHLIGHT);
        self::center(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' .
                    'Aliquam condimentum velit sit amet lacus interdum venenatis. Suspendisse potenti.',
        );

        self::newLine();

        self::console('This is just a warning, cool progress bar below: ', ConsoleLevel::WARNING);

        $this->initInfiniteBar();
        for ($i = 0; $i < 5; $i++) {
            $this->loopInfiniteBar();
        }
        $this->endInfiniteBar();

        self::console('And now another one: ', ConsoleLevel::WARNING);

        $this->initProgressBar(1000);
        for ($i = 0; $i < 50; $i++) {
            $this->addProgress(20);
            usleep(random_int(50000, 200000));
        }
        $this->endProgressBar();

        self::newLine(true);
        self::console('Bye :) ', ConsoleLevel::HIGHLIGHT);
        self::newLine(true);
    }

    public function otherHandler(Context $context): void
    {
        self::console('I\'m useless', ConsoleLevel::ERROR);
    }
}
