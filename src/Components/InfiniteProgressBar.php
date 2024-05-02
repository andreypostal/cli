<?php
namespace Andrey\Cli\Components;

trait InfiniteProgressBar
{
    private int $barSize;
    private int $startedProgressAt;

    public function initInfiniteBar(int $barSize = 30): void
    {
        $this->barSize = $barSize;
        $this->startedProgressAt = (int) microtime(true);
    }

    public function loopInfiniteBar(int $totalSteps = 20, int $blockSize = 3): void
    {
        $step = floor($this->barSize / $totalSteps);

        $originalBlockSize = $blockSize;
        for (
            $i = 0;
            $i <= $this->barSize;
            $i += $step, $blockSize = $originalBlockSize
        ) {
            if ($i === 0 || $i >= $this->barSize) {
                $blockSize = (int) floor($blockSize / 2);
            }

            $pct = $i / $this->barSize;

            $nLeftSpaces = ceil($this->barSize * $pct) - ceil($blockSize / 2);
            $nRightSpaces = $this->barSize - $nLeftSpaces - floor($blockSize / 2);

            $nRightSpaces = max(0, $nRightSpaces);
            $nLeftSpaces = max(0, $nLeftSpaces);

            $sumSpaces = $nLeftSpaces + $nRightSpaces + $blockSize;
            if ($sumSpaces !== $this->barSize) {
                if ($nRightSpaces > 0) {
                    $nRightSpaces += $this->barSize - $sumSpaces;
                } else {
                    $nLeftSpaces += $this->barSize - $sumSpaces;
                }
            }

            $leftSpaces = str_repeat(' ', $nLeftSpaces);
            $rightSpaces = str_repeat(' ', $nRightSpaces);

            $progressBarSquares = str_repeat("\u{2588}", $blockSize);

            $elapsed = round(microtime(true) - $this->startedProgressAt, 2);
            $elapsed = number_format($elapsed, 2);

            echo "\r{$leftSpaces}{$progressBarSquares}{$rightSpaces} elapsed: \e[1m{$elapsed} sec.\e[0m ";
            flush();

            usleep(20000);
        }
        $elapsed = round(microtime(true) - $this->startedProgressAt, 2);
        $elapsed = number_format($elapsed, 2);

        $spaces = str_repeat(' ', $this->barSize);
        echo "\r{$spaces} elapsed: \e[1m{$elapsed} sec.\e[0m ";
        usleep(40000);
    }

    public function endInfiniteBar(): void
    {
        for ($i = 1; $i <= $this->barSize; $i++) {
            $rightSpaces = str_repeat(' ', $this->barSize - $i);
            $progressBarSquares = str_repeat("\u{2588}", $i);

            $elapsed = round(microtime(true) - $this->startedProgressAt, 2);
            $elapsed = number_format($elapsed, 2);

            echo "\r{$progressBarSquares}{$rightSpaces} elapsed: \e[1m{$elapsed} sec.\e[0m ";
            flush();

            usleep(5000);
        }
        echo "\n";
    }
}
