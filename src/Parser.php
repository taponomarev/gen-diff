<?php

namespace Differ\Parser;

function parseFile($fileToPath)
{
    $firstPath = __DIR__ . $fileToPath;
    $secondPath = $fileToPath;
    $currentPath = '';

    if (file_exists($firstPath)) {
        $currentPath = $firstPath;
    } elseif ($secondPath) {
        $currentPath = $secondPath;
    } else {
        return;
    }

    $fileData = file_get_contents($currentPath);
    return json_decode($fileData, true);
}
