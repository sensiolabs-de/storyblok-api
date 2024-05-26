<?php

declare(strict_types=1);

/**
 * This file is part of Storyblok-Api.
 *
 * (c) SensioLabs Deutschland <info@sensiolabs.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\Storyblok\Api\Tests\Unit\Domain\Value;

use PHPUnit\Framework\TestCase;
use SensioLabs\Storyblok\Api\Domain\Value\Space;
use SensioLabs\Storyblok\Api\Tests\Util\FakerTrait;

final class SpaceTest extends TestCase
{
    use FakerTrait;

    /**
     * @test
     */
    public function fromArray(): void
    {
        $value = self::faker()->spaceResponse();
        $space = Space::fromArray($value);

        self::assertSame($value['id'], $space->id->value);
        self::assertSame($value['name'], $space->name);
        self::assertSame($value['domain'], $space->domain);
        self::assertSame($value['version'], $space->version);
        self::assertCount(\count($value['language_codes']), $space->languageCodes);
    }

    /**
     * @test
     *
     * @dataProvider provideRequiredKeys
     */
    public function fromArrayThrowsExceptionIfKeysAreMissing(string $key): void
    {
        $value = self::faker()->spaceResponse();
        unset($value[$key]);

        self::expectException(\InvalidArgumentException::class);

        Space::fromArray($value);
    }

    /**
     * @return \Generator<array{0: string}>
     */
    public static function provideRequiredKeys(): iterable
    {
        yield from [
            ['id'],
            ['name'],
            ['domain'],
            ['version'],
            ['language_codes'],
        ];
    }
}
