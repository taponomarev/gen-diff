<?php

namespace Differ\Cli;

use function App\Differ\genDiff;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]

DOC;

function run()
{
    $handler = new \Docopt\Handler(array(
        'help' => true,
        'version' => "Beta 1.0.0",
        'optionsFirst' => false,
    ));
    $params = $handler->handle(DOC);

    ['<firstFile>' => $firstPathToFile, '<secondFile>' => $secondPathToFile] = $params->args;

    $diff = genDiff($firstPathToFile, $secondPathToFile);
    print_r($diff);
}
