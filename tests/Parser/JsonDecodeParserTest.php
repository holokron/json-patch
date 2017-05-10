<?php

declare(strict_types=1);

namespace Holokron\JsonPatch\Tests\Parser;

use Holokron\JsonPatch\Parser\JsonDecodeParser;
use PHPUnit\Framework\TestCase;

class JsonDecodeParserTest extends TestCase
{
    /**
     * @var JsonDecodeParser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new JsonDecodeParser();
    }

    /**
     * @dataProvider dataParse
     */
    public function testParse(string $json, array $expected)
    {
        $result = $this->parser->parse($json);

        $this->assertSame($expected, $result);
    }

    public static function dataParse(): array
    {
        return [
            [
                '{"foo":"bar"}',
                [
                    'foo' => 'bar',
                ],
            ],
            [
                '{"foo":["foo","bar"],"bar":123}',
                [
                    'foo' => [
                        'foo',
                        'bar',
                    ],
                    'bar' => 123,
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataParseWhenInvalidJson
     * @expectedException \Holokron\JsonPatch\Exception\ParseException
     */
    public function testParseWhenInvalidJson(string $json)
    {
        $this->parser->parse($json);
    }

    public static function dataParseWhenInvalidJson(): array
    {
        return [
            [''],
            ['invalid json'],
            ['{"foo":"bar'],
        ];
    }
}
