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

final readonly class Story
{
    public function __construct(
        public Id $id,
        public Uuid $uuid,
        public ?Id $parentId,
        public ?Uuid $groupId,
        public string $name,
        public string $slug,
        public string $fullSlug,
        public ?string $path,
        public string $lang,
        public int $position,
        /**
         * @var list<string>
         */
        public array $tagList,
        public bool $isStartPage,
        public \DateTimeImmutable $firstPublishedAt,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $publishedAt,
        // release_id
        // sort_by_date
        // meta_data
        // alternates
        // default_full_slug
        // translated_slugs
        public string $type,
        /**
         * @var array<string, mixed>
         */
        public array $content,
    ) {
        TrimmedNonEmptyString::fromString($this->name);
        TrimmedNonEmptyString::fromString($this->slug);
    }

    /**
     * @param array<string, mixed> $values
     */
    public static function fromArray(array $values): self
    {
        foreach ([
            'id', 'uuid', 'name', 'slug', 'full_slug', 'lang', 'position', 'tag_list',
            'is_startpage', 'first_published_at', 'created_at', 'published_at', 'content',
        ] as $key) {
            Assert::keyExists($values, $key);
        }

        Assert::isArray($values['tag_list']);
        Assert::allString($values['tag_list']);

        Assert::stringNotEmpty($values['lang']);

        Assert::isArray($values['content']);
        Assert::keyExists($values['content'], 'component');
        Assert::stringNotEmpty($values['content']['component']);

        // $snakeToCamelCase = static fn (string $s) => preg_replace_callback('/_([a-z])/', static fn (array $matches) => strtoupper($matches[1]), $s);
        // $values = array_combine(array_map($snakeToCamelCase, array_keys($values)), $values);

        return new self(
            new Id($values['id']),
            new Uuid($values['uuid']),
            isset($values['parent_id']) ? new Id($values['parent_id']) : null,
            isset($values['group_id']) ? new Uuid($values['group_id']) : null,
            $values['name'],
            $values['slug'],
            $values['full_slug'],
            $values['path'] ?? null,
            $values['lang'],
            $values['position'],
            $values['tag_list'],
            $values['is_startpage'],
            DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['first_published_at']),
            DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['created_at']),
            DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['published_at']),
            // $values['meta_data'],
            // $values['alternates'],
            // $values['default_full_slug'],
            // $values['translated_slugs'],
            $values['content']['component'],
            $values['content'],
        );
    }
}
