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

use OskarStark\Value\TrimmedNonEmptyString;
use Webmozart\Assert\Assert;

/**
 * @see https://www.storyblok.com/docs/api/content-delivery/v2/datasources/the-datasource-entry-object
 */
final readonly class DatasourceEntry
{
    /**
     * @param string      $name           The complete name provided for the datasource entry
     * @param string      $value          Given value in the default dimension
     * @param null|string $dimensionValue Given value in the requested dimension
     */
    public function __construct(
        public Id $id,
        public string $name,
        public string $value,
        public ?string $dimensionValue = null,
    ) {
        TrimmedNonEmptyString::fromString($name);
        TrimmedNonEmptyString::fromString($value);

        if (null !== $dimensionValue) {
            TrimmedNonEmptyString::fromString($dimensionValue);
        }
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        Assert::same(['id', 'name', 'value', 'dimension_value'], array_keys($values));

        return new self(
            new Id($values['id']),
            $values['name'],
            $values['value'],
            $values['dimension_value'],
        );
    }
}
