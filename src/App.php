<?php
namespace Andrey\Cli;

use InvalidArgumentException;
use Andrey\Cli\Exceptions\CliException;

use Andrey\Cli\Types\Command;
use Andrey\Cli\Types\ConsoleLevel;
use Andrey\Cli\Types\Context;
use Andrey\Cli\Types\Param;

use Andrey\Cli\Utils\Output;

readonly class App
{
    use Output;

    private const HELP_CMD = 'help';

    /**
     * @throws CliException
     */
    public function __construct(
        private string $appName,
        private string $description,
        private string $cmd,
        /** @var Param[] */
        private array $params,
        /** @var Command[] */
        private array $commands,
    ) {
        if (PHP_SAPI !== 'cli') {
            throw new CliException('This tool can only be executed from CLI');
        }
    }

    public function run(array $argv): void
    {
        $key = mb_strtolower($argv[1] ?? 'default');

        $findArr = array_filter($this->commands, static fn(Command $cmd): bool => mb_strtolower($cmd->key) === $key);
        $command = array_values($findArr)[0] ?? null;

        if ($command === null && $key === self::HELP_CMD) {
            $this->helper();
            return;
        }

        $command ?? throw new InvalidArgumentException('Command not found.');
        $this->writeAppHeader($command);

        // Handle argv
        $argv = array_splice($argv, 1);
        $context = new Context(
            params: $this->parseParams(
                $command,
                [
                    'cmd',
                    ...$argv,
                ],
            ),
        );

        if (isset($command->service['handler']) && is_callable($command->service['handler'])) {
            $command->service['handler']($context);
            return;
        }

        if (is_string($command->service['instance'])) {
            $instance = new ($command->service['instance'])();
        } elseif (is_callable($command->service['instance'])) {
            $instance = $command->service['instance']();
        } else {
            throw new InvalidArgumentException('Invalid service provided to command '.$key);
        }

        if (!method_exists($instance, $command->service['entrypoint'])) {
            throw new InvalidArgumentException('Invalid entrypoint provided to command '.$key);
        }

        $instance->{$command->service['entrypoint']}($context);
    }

    private function writeAppHeader(Command $command): void
    {
        echo self::CLEAR;

        self::newLine();
        self::center("Starting Service: {$this->appName} - {$command->key}", ConsoleLevel::HIGHLIGHT);
        self::newLine();
    }

    /**
     * @return array<string, Param>
     */
    private function parseParams(Command $command, array $argv): array
    {
        $preparedParams = [
            'cmd' => new Param(
                key: 'cmd',
                flag: 'cmd',
                required: true,
            ),
        ];

        foreach ($this->params as $param) {
            $preparedParams[$param->key] = $param;
            $preparedParams[$param->flag] = $param;
        }

        foreach ($command->params as $param) {
            $preparedParams[$param->key] = $param;
            $preparedParams[$param->flag] = $param;
        }

        // Parse the parameters value based on the $argv content
        $output = [];
        $currentParam = null;
        foreach ($argv as $arg) {
            $arg = trim(ltrim($arg, '-'));
            if ($arg === '') {
                continue;
            }

            if ($currentParam === null) {
                $currentParam = $preparedParams[$arg] ?? null;
                if ($currentParam?->isBool) {
                    $output[$currentParam->key] = $currentParam->withValue(true);
                    $output[$currentParam->flag] = $currentParam->withValue(true);

                    unset($preparedParams[$currentParam->key], $preparedParams[$currentParam->flag]);
                    $currentParam = null;
                }
                continue;
            }

            $output[$currentParam->key] = $currentParam->withValue($arg);
            $output[$currentParam->flag] = $currentParam->withValue($arg);
            unset($preparedParams[$currentParam->key], $preparedParams[$currentParam->flag]);
            $currentParam = null;
        }

        // The remaining parameters (not present in the $argv) still in the $preparedParams
        // We need to validate required parameters and assign the ones with default values as well
        foreach ($preparedParams as $param) {
            if ($param->required) {
                throw new InvalidArgumentException("Validation Error: Field {$param->key} is required.");
            }

            if ($param->defaultValue !== null) {
                $output[$param->key] = $param->withValue($param->defaultValue);
                $output[$param->flag] = $param->withValue($param->defaultValue);
            }
        }

        return $output;
    }

    private function helper(): void
    {
        echo self::CLEAR;
        self::newLine();
        self::center($this->appName, ConsoleLevel::HIGHLIGHT);
        self::center($this->description);

        $close = self::STYLE_CLOSE;
        $bold = self::STYLE_BOLD;

        $globalParams = '';
        foreach ($this->params as $param) {
            $globalParams .= $this->parseParamHelper($param);
        }

        $commands = '';
        foreach ($this->commands as $command) {
            $commands .= $this->parseCommandHelper($command);
        }

        echo <<<HELPER

  {$bold}Global Params:{$close}
{$globalParams}
{$commands}
HELPER;
    }

    private function parseCommandHelper(Command $command): string
    {
        $eol = PHP_EOL;
        $close = self::STYLE_CLOSE;
        $bold = self::STYLE_BOLD;
        $gray = self::STYLE_GRAY;

        $hasParams = count($command->params) > 0;

        $paramsUsage = '';
        $params = '';
        foreach ($command->params as $param) {
            $params .= $this->parseParamHelper($param);

            $value = $param->isBool ? '' : '<value> ';
            $paramsUsage .= "-{$param->flag} {$value}";
        }

        $finalParams = <<<PARAMS
    {$bold}Params:{$close}
{$params} {$eol}
PARAMS;
        if (!$hasParams) {
            $finalParams = '';
        }

        return <<<COMMAND
  {$bold}Command <{$command->key}> Usage:{$close}
    {$gray}{$this->cmd} {$command->key} {$paramsUsage}[...globals]{$close}
{$finalParams}
COMMAND;
    }

    private function parseParamHelper(Param $param): string
    {
        $eol = PHP_EOL;

        $close = self::STYLE_CLOSE;
        $bold = self::STYLE_BOLD;
        $red = self::STYLE_RED;

        $required = $param->required ? "{$bold}{$red}* {$close}" : '  ';

        return <<<PARAM
   {$required}--{$param->key} [-{$param->flag}] | {$param->helpText} {$eol}
PARAM;
    }
}
