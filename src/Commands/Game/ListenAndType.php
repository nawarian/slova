<?php

declare(strict_types=1);

namespace Nawarian\Slova\Commands\Game;

use Nawarian\Slova\Repository\Dictionary;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListenAndType extends Command
{
    protected static $defaultName = 'game:listenandtype';
    protected static $defaultDescription = 'Listen to an audio with a Russian word and type it in.';

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

        Welcome to <fg=green>Listen and Type</>!
        You'll be prompted with a random russian word. Just type it back to score!
        TEXT);

        do {
            $start = $style->ask('Type <fg=yellow>пойдем</> to get started');
        } while ($start !== 'пойдем');


        $score = 0;
        $level = 3;
        $cursor = new Cursor($output);
        while ($word = $this->dict->randomNoun($level)) {
            $audioFile = __DIR__ . "/../../resources/shtooka/{$word->russian}.mp3";
            $audioExists = file_exists($audioFile);
            if (!$audioExists) {
                continue;
            }

            $cursor->clearScreen();
            $cursor->moveToPosition(0, 0);

            $chars = mb_strlen($word->russian);
            $style->success("Your score: {$score}");
            $style->info("Playing your word! It has {$chars} characters and means '{$word->english}'.");
            $attempts = 0;
            while (true) {
                exec("afplay {$audioFile} & > /dev/null");
                $typedWord = $style->ask(mb_substr($word->russian, 0, $attempts), '');

                if (mb_strtolower(trim($typedWord)) === mb_strtolower($word->russian)) {
                    $score += $chars - $attempts;
                    exec("afplay {$audioFile} & > /dev/null");
                    break;
                } else {
                    $style->error('Incorrect input.');
                    $attempts++;
                }
            }

            if ($level * 3 > $score) {
                $level++;
            }
        }

        return 0;
    }
}
