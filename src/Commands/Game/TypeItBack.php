<?php

declare(strict_types=1);

namespace Nawarian\Slova\Commands\Game;

use Nawarian\Slova\Repository\Dictionary;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class TypeItBack extends Command
{
    protected static $defaultName = 'game:typeitback';
    protected static $defaultDescription = 'Receive random Russian words and type them back to score points.';

    private Dictionary $dict;

    public function __construct(Dictionary $dict)
    {
        parent::__construct();

        $this->dict = $dict;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $style->text(<<<TEXT
        <fg=green>Здравствуйте!</>

        Welcome to <fg=green>Type it Back</>!
        You'll be prompted with a random russian word. Just type it back to score!
        TEXT);

        do {
            $start = $style->ask('Type <fg=yellow>пойдем</> to get started');
        } while ($start !== 'пойдем');


        $score = 0;
        $level = 3;
        $wrong = [];
        $cursor = new Cursor($output);
        while ($word = $this->dict->randomNoun($level)) {
            if (count($wrong) > 5) {
                $word = array_shift($wrong);
            }

            $audioFile = __DIR__ . "/../../resources/shtooka/{$word->russian}.mp3";
            $audioExists = file_exists($audioFile);
            if (!$audioExists) {
                continue;
            }

            $cursor->clearScreen();
            $cursor->moveToPosition(0, 0);

            $attempts = 0;
            $style->success("Your score: {$score}");
            $style->writeln("The word is <fg=yellow>'{$word->russian}'</> ({$word->english})");
            while ($attempts < 3) {
                exec("afplay {$audioFile} & > /dev/null");
                $typedWord = $style->ask('', '');

                if (mb_strtolower(trim($typedWord)) === mb_strtolower($word->russian)) {
                    $score += mb_strlen($word->russian);
                    exec("afplay {$audioFile} & > /dev/null");
                    break;
                }
                $attempts++;
            }

            if ($attempts === 3) {
                $wrong[] = $word;
                shuffle($wrong);
            }

            if ($level * 3 > $score) {
                $level++;
            }
        }

        return 0;
    }
}
