<?php
namespace Andrey\Cli\Utils;

use Andrey\Cli\Types\ConsoleLevel;

trait Output
{
    protected const CLEAR = "\033[2J\033[H";

    protected const STYLE_CLOSE = "\e[0m";
    protected const STYLE_BOLD = "\e[1m";

    protected const STYLE_RED = "\e[91m";
    protected const STYLE_GRAY = "\e[37m";

    public static function console(string $message, ConsoleLevel $level = ConsoleLevel::NORMAL): void
    {
        echo sprintf(
            "\e[1m\e[36m[%s]: %s%s\e[0m\n",
            date('d/m/Y H:i:s'),
            $level->getColor(),
            $message,
        );

        flush();
    }

    public static function center(string $message, ConsoleLevel $level = ConsoleLevel::NORMAL, int $columns = 60): void
    {
        $messageOriginal = $message;
        $nChars = floor($columns * .8);

        $message = substr($message, 0, $nChars);
        $spaces = str_repeat(' ', floor(($columns - strlen($message)) / 2));

        echo "{$spaces} {$level->getColor()}{$message}\e[0m\n";

        if (strlen($messageOriginal) > $columns) {
            self::center(substr($messageOriginal, $nChars), $level, $columns);
        }
    }

    public static function newLine(bool $time = false): void
    {
        if ($time) {
            echo sprintf("\r\e[1m\e[36m[%s]:", date('d/m/Y H:i:s'));
        }
        echo "\n";
    }
}
