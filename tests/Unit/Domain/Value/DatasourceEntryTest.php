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
use SensioLabs\Storyblok\Api\Domain\Value\DatasourceEntry;
use SensioLabs\Storyblok\Api\Tests\Util\FakerTrait;

final class DatasourceEntryTest extends TestCase
{
    use FakerTrait;

    /**
     * @test
     */
    public function name(): void
    {
        $values = self::faker()->datasourceEntryResponse();

        self::assertSame($values['name'], DatasourceEntry::fromArray($values)->name);
    }

    /**
     * @test
     *
     * @dataProvider provideRequiredKeys
     */
    public function missingKey(string $key): void
    {
        $values = self::faker()->datasourceEntryResponse();
        unset($values[$key]);

        self::expectException(\InvalidArgumentException::class);

        DatasourceEntry::fromArray($values);
    }

    /**
     * @return iterable<array{0: string}>
     */
    public static function provideRequiredKeys(): iterable
    {
        return [
            ['id'],
            ['name'],
            ['value'],
            ['dimension_value'],
        ];
    }

    /**
     * @test
     */
    public function invalidName(): void
    {
        $values = self::faker()->datasourceEntryResponse();
        $values['name'] = ' ';

        self::expectException(\InvalidArgumentException::class);

        DatasourceEntry::fromArray($values);
    }

    /**
     * @test
     */
    public function invalidValue(): void
    {
        $values = self::faker()->datasourceEntryResponse();
        $values['value'] = ' ';

        self::expectException(\InvalidArgumentException::class);

        DatasourceEntry::fromArray($values);
    }
}
