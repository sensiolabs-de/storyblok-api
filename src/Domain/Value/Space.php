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

final readonly class Space
{
    /**
     * @param list<string> $languageCodes
     */
    public function __construct(
        public Id $id,
        public string $name,
        public string $domain,
        public int $version,
        public array $languageCodes,
    ) {
        TrimmedNonEmptyString::fromString($name);
        TrimmedNonEmptyString::fromString($domain);

        foreach ($languageCodes as $languageCode) {
            TrimmedNonEmptyString::fromString($languageCode);
        }
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        Assert::same(array_keys($values), ['id', 'name', 'domain', 'version', 'language_codes']);

        Assert::isArray($values['language_codes']);

        return new self(
            new Id($values['id']),
            $values['name'],
            $values['domain'],
            $values['version'],
            $values['language_codes'],
        );
    }
}
