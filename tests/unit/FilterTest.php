<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\TestCase;

class FilterTest extends TestCase
{
    public static function invalidMentionsProvider(): array
    {
        return [
            ['@@name'],
            ['@!name'],
            [' &amp;=@name'],
            ['.org/path#@name'],
            ['https://phpc.social/@name/123'],
            ['https://en.wikipedia.org/wiki/%22@%22_%28album%29'],
        ];
    }

    public static function validMentionsProvider(): array
    {
        // Do not test entire opening anchor tag to avoid breaking if HTML format changes.
        return [
            ['">@name ', '>@name</a>'],
            ['li>@1name', '>@1name</a>'],
            ['@name-1', '>@name-1</a>'],
            ['@__name__', '>@__name__</a>'],
            ['.@name!', '>@name</a>'],
            ['(@name, @name2)', '>@name</a>'],
            ['(@name, @name2)', '>@name2</a>'], // verify both are there.
        ];
    }

    #[DataProvider('invalidMentionsProvider')]
    public function testInvalidMentions(string $input): void
    {
        $this->assertEquals($input, highlightMentions($input)); // No changes.
    }

    #[DataProvider('validMentionsProvider')]
    public function testValidMentions(string $input, string $needle): void
    {
        $parsed = highlightMentions($input);
        $this->assertStringContainsString($needle, $parsed); // Expected transformations.
    }

    public function testUnicodeMentions(): void
    {
        if (hasUnicodeSupport()) {
            $this->assertStringContainsString('>@nämé</a>', highlightMentions('>@nämé '));
        } else {
            $this->markTestSkipped('Unicode support not available.');
        }
    }
}
