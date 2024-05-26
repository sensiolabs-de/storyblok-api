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
use Safe\DateTimeImmutable;
use Webmozart\Assert\Assert;

/**
 * @see https://www.storyblok.com/docs/api/content-delivery/v2/datasources/the-datasource-object
 */
final readonly class DatasourceDimension
{
    public function __construct(
        public Id $id,
        public string $name,
        public string $entryValue,
        public Id $datasourceId,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
    ) {
        TrimmedNonEmptyString::fromString($this->name);
        TrimmedNonEmptyString::fromString($this->entryValue);
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        Assert::same(['id', 'name', 'entry_value', 'datasource_id', 'created_at', 'updated_at'], array_keys($values));

        return new self(
            new Id($values['id']),
            $values['name'],
            $values['entry_value'],
            new Id($values['datasource_id']),
            DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['created_at']),
            DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['updated_at']),
        );
    }
}
