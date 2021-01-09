<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

/**
 * @covers Differ\Tests\DifferTest
 * @covers Differ\Differ\genDiff
 * @covers Differ\Parser\parseFile
 */

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testGenDiffSuccess(string $filename1, string $filename2, string $format, string $resultFilename): void
    {
        $actualString = genDiff(
            $this->generateFixturePath($filename1), 
            $this->generateFixturePath($filename2), 
            $format
        );
        $this->assertStringEqualsFile(
            $this->generateFixturePath($resultFilename), 
            $actualString
        );
    }

    public function generateFixturePath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, ['tests', 'fixtures', $filename]);
    }

    public function additionProvider(): array
    {
        return [
            ['file1.json', 'file2.json', 'stylish', 'result_json.txt'],
            ['file1.yml', 'file2.yml', 'stylish', 'result_yml.txt'],
        ];
    }
}