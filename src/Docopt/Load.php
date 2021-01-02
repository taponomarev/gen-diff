<?php

namespace Gendiff\Docopt\Load;

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

function load()
{
    $handler = new \Docopt\Handler(array(
        'help'=>true,
        'version'=>"Beta 1.0.0",
        'optionsFirst'=>false,
    ));
    $handler->handle(DOC);
}