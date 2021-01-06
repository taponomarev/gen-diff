<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private $firstPath;
    private $secondPath;

    public function setUp(): void
    {
        $this->firstPath = __DIR__ . '/fixtures/file1.json';
        $this->secondPath = __DIR__ . '/fixtures/file2.json';
    }

    /**
     * @covers       \Differ\Differ\testGenDiff
     */
    public function testGenDiff()
    {
        $ast = [
            '-follow' => false,
            'host' => 'hexlet.io',
            '-proxy' => '123.234.53.22',
            '-timeout' => 50,
            '+timeout' => 20,
            '+verbose' => true
        ];
        $expected = \json_encode($ast, JSON_PRETTY_PRINT) . PHP_EOL;
        $actual = genDiff($this->firstPath, $this->secondPath);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers       \Differ\Differ\testGenDiff
     */
    public function testWrongGenDiff()
    {
        $ast = [];
        $expected = \json_encode($ast, JSON_PRETTY_PRINT) . PHP_EOL;
        $actual = genDiff($this->firstPath, $this->secondPath);
        $this->assertFalse($expected == $actual);
    }
}