<?php

namespace Gendiff\Docopt\Load;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version

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