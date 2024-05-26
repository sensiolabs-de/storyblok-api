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

namespace SensioLabs\Storyblok\Api;

use SensioLabs\Storyblok\Api\Domain\Value\Dto\Pagination;
use SensioLabs\Storyblok\Api\Domain\Value\Dto\SortBy;
use SensioLabs\Storyblok\Api\Domain\Value\Filter\FilterCollection;
use SensioLabs\Storyblok\Api\Domain\Value\Id;
use SensioLabs\Storyblok\Api\Domain\Value\Uuid;
use SensioLabs\Storyblok\Api\Response\StoriesResponse;
use SensioLabs\Storyblok\Api\Response\StoryResponse;

/**
 * @author Silas Joisten <silas.joisten@proton.me>
 */
interface StoriesApiInterface
{
    public const int PER_PAGE = 25;
    public const int MAX_PER_PAGE = 100;

    public function all(string $locale = 'default', ?Pagination $pagination = null, ?SortBy $sortBy = null, ?FilterCollection $filters = null): StoriesResponse;

    public function allByContentType(string $contentType, string $locale = 'default', ?Pagination $pagination = null, ?SortBy $sortBy = null, ?FilterCollection $filters = null): StoriesResponse;

    public function bySlug(string $slug, string $locale = 'default'): StoryResponse;

    public function byUuid(Uuid $uuid, string $locale = 'default'): StoryResponse;

    public function byId(Id $id, string $locale = 'default'): StoryResponse;
}
