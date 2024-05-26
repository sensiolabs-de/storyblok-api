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
use SensioLabs\Storyblok\Api\Domain\Value\DatasourceDimension;
use SensioLabs\Storyblok\Api\Tests\Util\FakerTrait;

final class DatasourceDimensionTest extends TestCase
{
    use FakerTrait;

    /**
     * @test
     */
    public function fromArray(): void
    {
        $response = self::faker()->datasourceResponse()['dimensions'][0];

        $dimension = DatasourceDimension::fromArray($response);

        self::assertSame($response['id'], $dimension->id->value);
        self::assertSame($response['name'], $dimension->name);
        self::assertSame($response['entry_value'], $dimension->entryValue);
        self::assertSame($response['datasource_id'], $dimension->datasourceId->value);
        self::assertSame($response['created_at'], $dimension->createdAt->format('Y-m-d\TH:i:s.v\Z'));
        self::assertSame($response['updated_at'], $dimension->updatedAt->format('Y-m-d\TH:i:s.v\Z'));
    }
}
