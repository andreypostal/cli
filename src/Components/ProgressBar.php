<?php
namespace Andrey\Cli\Components;

trait ProgressBar
{
    private const MAX_MESSAGE_SIZE = 50;
    private int $totalItems;
    private int $barSize;
    private int $currentProgress;
    private int $startedProgressAt;

    public function initProgressBar(int $total, int $barSize = 30): void
    {
        $this->totalItems = $total;
        $this->barSize = $barSize;
        $this->currentProgress = 0;
        $this->startedProgressAt = (int) microtime(true);

        $this->addProgress(0);
    }

    public function addProgress(int $progress, string $message = ''): void
    {
        $this->currentProgress += $progress;

        $pct = $this->currentProgress / max($this->totalItems, 1);
        $progressBarSize = floor($pct * $this->barSize);

        $progressBarSquares = str_repeat("\u{2588}", $progressBarSize);
        $spaces = str_repeat(' ', $this->barSize - $progressBarSize);
        $pct = round($pct * 100);

        $rate = (microtime(true) - $this->startedProgressAt) / max($this->currentProgress, 1);
        $left = $this->totalItems - $this->currentProgress;
        $estimate = number_format(round($rate * $left, 2), 2);
        $elapsed = round(microtime(true) - $this->startedProgressAt, 2);
        $elapsed = number_format($elapsed, 2);

        $pipe = $message !== '' ? ' | ' : '';

        $endSpaces = '';
        if ($message !== '') {
            $message = substr($message, 0, self::MAX_MESSAGE_SIZE);
            $endSpaces = str_repeat(' ', self::MAX_MESSAGE_SIZE - strlen($message));
        }

        echo
            "\r{$progressBarSquares}{$spaces}" .
            "\e[1m {$pct}% {$this->currentProgress}/{$this->totalItems}\e[0m " .
            "remaining: \e[1m{$estimate} sec.\e[0m elapsed: \e[1m{$elapsed} sec.\e[0m{$pipe}{$message}{$endSpaces}";

        flush();
    }

    public function endProgressBar(): void
    {
        echo "\n";
    }
}
