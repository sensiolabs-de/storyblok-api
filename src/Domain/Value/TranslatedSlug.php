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

final readonly class TranslatedSlug
{
    public function __construct(
        public string $slug,
        public string $name,
        public string $lang,
        public bool $published,
    ) {
        TrimmedNonEmptyString::fromString($this->slug);
        TrimmedNonEmptyString::fromString($this->name);
        TrimmedNonEmptyString::fromString($this->lang);
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        Assert::same(array_keys($values), ['slug', 'name', 'lang', 'published']);

        return new self(
            $values['slug'],
            $values['name'],
            $values['lang'],
            $values['published'],
        );
    }
}
