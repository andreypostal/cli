<?php
namespace Andrey\Cli\Types;

enum ConsoleLevel
{
    case NORMAL;
    case INFO;
    case WARNING;
    case ERROR;
    case SUCCESS;
    case HIGHLIGHT;

    public function getColor(): string
    {
        return match($this) {
            self::NORMAL => "\e[0m",
            self::INFO => "\e[94m",
            self::WARNING => "\e[93m",
            self::ERROR => "\e[91m",
            self::SUCCESS => "\e[92m",
            self::HIGHLIGHT => "\e[1;95m",
        };
    }
}
