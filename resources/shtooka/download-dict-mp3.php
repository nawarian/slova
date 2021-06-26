<?php

declare(strict_types=1);

$sources = [
    'rus-balm-voc',
    'rus-balm-voc-sakhno',
    'rus-nonfree',
];

foreach ($sources as $source) {
    $index = new DOMDocument();
    $index->load(__DIR__ . "/{$source}.xml");

    $xpath = new DOMXPath($index);
    $fileQuery = $xpath->query('group/file');

    /** @var DOMElement $file */
    foreach ($fileQuery as $file) {
        $filename = $file->attributes->getNamedItem('path')->nodeValue;
        $word = $xpath->query('tag', $file)
            ->item(0)
            ->attributes
            ->getNamedItem('swac_text')
            ->nodeValue;

        if (!file_exists(__DIR__ . "/{$word}.mp3")) {
            $mp3 = file_get_contents("http://packs.shtooka.net/{$source}/mp3/{$filename}");
            file_put_contents(__DIR__ . "/{$word}.mp3", $mp3);
        }
    }
}

