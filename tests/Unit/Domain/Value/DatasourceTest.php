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
use SensioLabs\Storyblok\Api\Domain\Value\Datasource;
use SensioLabs\Storyblok\Api\Tests\Util\FakerTrait;

final class DatasourceTest extends TestCase
{
    use FakerTrait;

    /**
     * @test
     */
    public function entries(): void
    {
        $response = self::faker()->datasourceResponse();

        $datasource = Datasource::fromArray($response);

        self::assertSame($response['name'], $datasource->name);
        self::assertSame($response['slug'], $datasource->slug);
        self::assertSame($response['id'], $datasource->id->value);
        self::assertCount(\count($response['dimensions']), $datasource->dimensions);
    }
}
