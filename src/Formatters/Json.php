<?php

namespace Differ\Formatters\Json;

function buildFormat(array $three): string
{
    return (string) json_encode($three, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
