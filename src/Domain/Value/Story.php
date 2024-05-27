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
    public string $name;
    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $publishedAt;
    public Id $id;
    public Uuid $uuid;

    /**
     * @var array<string, mixed>
     */
    public array $content;
    public string $slug;
    public string $fullSlug;
    public ?string $sortByDate;
    public int $position;

    /**
     * @var list<string>
     */
    public array $tagList;
    public bool $isStartPage;
    public ?Id $parentId;
    public ?Uuid $groupId;
    public \DateTimeImmutable $firstPublishedAt;
    public ?int $releaseId;
    public string $lang;
    public ?string $path;

    /**
     * @var list<array<mixed>>
     */
    public array $alternates;
    public ?string $defaultFullSlug;

    /**
     * @var list<TranslatedSlug>
     */
    public array $translatedSlugs;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values)
    {
        Assert::keyExists($values, 'id');
        Assert::integer($values['id']);
        $this->id = new Id($values['id']);

        Assert::keyExists($values, 'uuid');
        Assert::string($values['uuid']);
        $this->uuid = new Uuid($values['uuid']);

        Assert::keyExists($values, 'parent_id');
        Assert::nullOrInteger($values['parent_id']);

        if (null !== $values['parent_id']) {
            $parentId = new Id($values['parent_id']);
        }

        $this->parentId = $parentId ?? null;

        Assert::keyExists($values, 'group_id');
        Assert::nullOrString($values['group_id']);

        if (null !== $values['group_id']) {
            $groupId = new Uuid($values['group_id']);
        }

        $this->groupId = $groupId ?? null;

        Assert::keyExists($values, 'name');
        Assert::string($values['name']);
        $this->name = TrimmedNonEmptyString::fromString($values['name'])->toString();

        Assert::keyExists($values, 'slug');
        Assert::string($values['slug']);
        $this->slug = TrimmedNonEmptyString::fromString($values['slug'])->toString();

        Assert::keyExists($values, 'full_slug');
        Assert::string($values['full_slug']);
        $this->fullSlug = TrimmedNonEmptyString::fromString($values['full_slug'])->toString();

        Assert::keyExists($values, 'path');
        Assert::nullOrString($values['path']);

        if (null !== $values['path']) {
            $path = TrimmedNonEmptyString::fromString($values['path'])->toString();
        }

        $this->path = $path ?? null;

        Assert::keyExists($values, 'lang');
        Assert::stringNotEmpty($values['lang']);
        $this->lang = $values['lang'];

        Assert::keyExists($values, 'position');
        Assert::integer($values['position']);
        $this->position = $values['position'];

        Assert::keyExists($values, 'tag_list');
        Assert::isArray($values['tag_list']);
        Assert::allString($values['tag_list']);
        $this->tagList = $values['tag_list'];

        Assert::keyExists($values, 'is_startpage');
        Assert::boolean($values['is_startpage']);
        $this->isStartPage = $values['is_startpage'];

        Assert::keyExists($values, 'published_at');
        Assert::stringNotEmpty($values['published_at']);
        $this->firstPublishedAt = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['first_published_at']);

        Assert::keyExists($values, 'created_at');
        Assert::stringNotEmpty($values['created_at']);
        $this->createdAt = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['created_at']);

        Assert::keyExists($values, 'published_at');
        Assert::stringNotEmpty($values['published_at']);
        $this->publishedAt = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i:s.v\Z', $values['published_at']);

        Assert::keyExists($values, 'content');
        Assert::isArray($values['content']);
        $this->content = $values['content'];

        Assert::keyExists($values, 'sort_by_date');
        Assert::nullOrString($values['sort_by_date']);
        $this->sortByDate = $values['sort_by_date'];

        Assert::keyExists($values, 'release_id');
        Assert::nullOrInteger($values['release_id']);
        $this->releaseId = $values['release_id'];

        Assert::keyExists($values, 'alternates');
        Assert::isArray($values['alternates']);
        $this->alternates = $values['alternates'];

        if (\array_key_exists('default_full_slug', $values)) {
            Assert::string($values['default_full_slug']);
            $defaultFullSlug = TrimmedNonEmptyString::fromString($values['default_full_slug'])->toString();
        }

        $this->defaultFullSlug = $defaultFullSlug ?? null;

        if (\array_key_exists('translated_slugs', $values)) {
            Assert::isArray($values['translated_slugs']);
            $translatedSlugs = array_map(TranslatedSlug::fromArray(...), $values['translated_slugs']);
        }

        $this->translatedSlugs = $translatedSlugs ?? [];
    }
}
