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
use SensioLabs\Storyblok\Api\Domain\Value\Total;
use SensioLabs\Storyblok\Api\Domain\Value\Uuid;
use SensioLabs\Storyblok\Api\Response\StoriesResponse;
use SensioLabs\Storyblok\Api\Response\StoryResponse;
use Webmozart\Assert\Assert;

final readonly class StoriesApi implements StoriesApiInterface
{
    private const string ENDPOINT = '/v2/cdn/stories/';

    public function __construct(
        private StoryblokClientInterface $client,
    ) {
    }

    public function all(string $locale = 'default', ?Pagination $pagination = null, ?SortBy $sortBy = null, ?FilterCollection $filters = null): StoriesResponse
    {
        return $this->getAll([], $locale, $pagination, $sortBy, $filters);
    }

    public function allByContentType(string $contentType, string $locale = 'default', ?Pagination $pagination = null, ?SortBy $sortBy = null, ?FilterCollection $filters = null): StoriesResponse
    {
        Assert::stringNotEmpty($contentType);

        return $this->getAll(['contentType' => $contentType], $locale, $pagination, $sortBy, $filters);
    }

    public function bySlug(string $slug, string $locale = 'default'): StoryResponse
    {
        return $this->getOne($slug, $locale);
    }

    public function byId(Id $id, string $locale = 'default'): StoryResponse
    {
        return $this->getOne((string) $id->value, $locale);
    }

    public function byUuid(Uuid $uuid, string $locale = 'default'): StoryResponse
    {
        return $this->getOne($uuid->value, $locale, ['find_by' => 'uuid']);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function getAll(array $parameters, string $locale = 'default', ?Pagination $pagination = null, ?SortBy $sortBy = null, ?FilterCollection $filters = null): StoriesResponse
    {
        Assert::stringNotEmpty($locale);

        $pagination ??= new Pagination(1, self::PER_PAGE);
        Assert::lessThanEq($pagination->perPage, self::MAX_PER_PAGE);

        if (null !== $sortBy) {
            $parameters['sort_by'] = sprintf('%s:%s', $sortBy->field, $sortBy->direction->value);
        }

        if (null !== $filters) {
            $parameters['filter_query'] = $filters->toArray();
        }

        $response = $this->client->request('GET', self::ENDPOINT, [
            'query' => [
                ...$parameters,
                'language' => $locale,
                'page' => $pagination->page,
                'per_page' => $pagination->perPage,
            ],
        ]);

        return new StoriesResponse(Total::fromHeaders($response->getHeaders()), $pagination, $response->toArray());
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function getOne(string $identifier, string $locale = 'default', array $parameters = []): StoryResponse
    {
        Assert::stringNotEmpty($locale);
        Assert::stringNotEmpty($identifier);

        $response = $this->client->request('GET', self::ENDPOINT.$identifier, [
            'query' => [
                ...$parameters,
                'language' => $locale,
                'fallback_lang' => 'default',
            ],
        ]);

        return new StoryResponse($response->toArray());
    }
}
