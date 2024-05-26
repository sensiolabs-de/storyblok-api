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

namespace SensioLabs\Storyblok\Api\Domain\Value;

use Webmozart\Assert\Assert;

/**
 * @see https://www.storyblok.com/docs/api/content-delivery/v2/datasources/the-datasource-object
 */
final readonly class Datasource
{
    public function __construct(
        public Id $id,
        /**
         * The complete name provided for the datasource.
         */
        public string $name,
        /**
         * The unique slug of the datasource.
         */
        public string $slug,
        /**
         * The dimensions (e.g., per country, region, language, or other context) defined for the datasource.
         *
         * @var array<DatasourceDimension>
         */
        public array $dimensions,
    ) {
        Assert::notEmpty($name);
        Assert::notEmpty($slug);
        Assert::allIsInstanceOf($dimensions, DatasourceDimension::class);
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        Assert::same(['id', 'name', 'slug', 'dimensions'], array_keys($values));

        Assert::isArray($values['dimensions']);
        $dimensions = array_map(DatasourceDimension::fromArray(...), $values['dimensions']);

        return new self(
            new Id($values['id']),
            $values['name'],
            $values['slug'],
            $dimensions,
        );
    }
}
