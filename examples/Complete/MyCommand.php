<?php
namespace Andrey\Cli\Examples\Complete;

use Andrey\Cli\App;
use Andrey\Cli\Types\Command;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Types\Param;

readonly class MyCommand extends App
{
    public function __construct()
    {
        parent::__construct(
            appName: 'Amazing App',
            description:
                'This is an amazing app that showcases how easy  it is to create a CLI application.',
            cmd: 'php app',
            params: [
                new Param(
                    key: 'user',
                    flag: 'u',
                    required: true,
                    helpText: 'I\'m a global param that all commands will have access to',
                ),
                new Param(
                    key: 'watch',
                    flag: 'w',
                    helpText: 'I\'m just watching you',
                    isBool: true,
                ),
            ],
            commands: [
                new Command(
                    key: 'handler',
                    description: 'Please use me to log in',
                    service: [
                        'handler' => static function(Context $context): void {
                            $cmd = $context->get('cmd');
                            $user = $context->get('user');

                            App::console("Executing the command `{$cmd}` with user `{$user}`");
                        },
                    ],
                ),
                new Command(
                    key: 'login',
                    description: 'Please use me to log in',
                    service: [
                        'instance' => MyService::class,
                        'entrypoint' => 'oneHandler',
                    ],
                    params: [
                        new Param(
                            key: 'alg',
                            flag: 'a',
                            helpText: 'Alg stands for... something about cars?',
                        ),
                        new Param(
                            key: 'password',
                            flag: 'p',
                            required: true,
                            helpText: 'Secured password',
                        ),
                        new Param(
                            key: 'scope',
                            flag: 's',
                            helpText: 'We need more params',
                        ),
                    ],
                ),
                new Command(
                    key: 'other',
                    description: '',
                    service: [
                        'instance' => MyService::class,
                        'entrypoint' => 'otherHandler',
                    ],
                    params: [
                        new Param(
                            key: 'foo',
                            flag: 'f',
                            helpText: 'bar?',
                        ),
                    ],
                ),
            ],
        );
    }
}
