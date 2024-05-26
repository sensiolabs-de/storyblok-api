# storyblok-api

| Branch    | PHP                                         | Code Coverage                                                                                                                                       |
|-----------|---------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|
| `master`  | [![PHP](https://github.com/sensiolabs-de/storyblok-api/actions/workflows/ci.yaml/badge.svg)](https://github.com/sensiolabs-de/storyblok-api/actions/workflows/ci.yaml)  | [![codecov](https://codecov.io/gh/sensiolabs-de/storyblok-api/graph/badge.svg?token=8K4F33LSWF)](https://codecov.io/gh/sensiolabs-de/storyblok-api) |

## Usage

### Installation

```bash
composer require sensiolabs-de/storyblok-api
```

### Setup

```php
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(
    baseUri: 'https://api.storyblok.com',
    token: '***********',
    timeout: 10 // optional
);

// you can now request any endpoint which needs authentication
$client->request('GET', '/api/something', $options);
```

## Spaces

In your code you should type-hint to `SensioLabs\Storyblok\Api\SpacesApiInterface`

### Get the current space

Returns the space associated with the current token.

```php
use SensioLabs\Storyblok\Api\SpacesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);
$spacesApi = new SpacesApi($client);

$response = $spacesApi->me();

// Example response
$space = $response->getSpace();
$space->getName(); // Example Space
$space->getDomain(); // https:/example.com
$space->getVersion(); // 1716713992
$space->getLanguages(); // ['de', 'en']
```

## Stories

In your code you should type-hint to `SensioLabs\Storyblok\Api\StoriesApiInterface`

### Get all available stories

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->all(locale: 'de');
```

### Pagination

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Dto\Pagination;

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->all(
    locale: 'de',
    pagination: new Pagination(page: 1, perPage: 30)
);
```

#### Sorting

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Dto\SortBy;
use SensioLabs\Storyblok\Api\Domain\Value\Dto\Direction;

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->all(
    locale: 'de',
    sortBy: new SortBy(field: 'title', direction: Direction::Desc)
);
```

#### Filtering

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Filter\FilterCollection;
use SensioLabs\Storyblok\Api\Domain\Value\Dto\Direction;
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\InFilter;

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->all(
    locale: 'de',
    filters: new FilterCollection([
        new InFilter(field: 'single_reference_field', value: 'f2fdb571-a265-4d8a-b7c5-7050d23c2383')
    ])
);
```

#### Available filters

[AllInArrayFilter.php](src/Domain/Value/Filter/Filters/AllInArrayFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\AllInArrayFilter;

new AllInArrayFilter(field: 'tags', value: ['foo', 'bar', 'baz']);
```

[AnyInArrayFilter.php](src/Domain/Value/Filter/Filters/AnyInArrayFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\AnyInArrayFilter;

new AnyInArrayFilter(field: 'tags', value: ['foo', 'bar', 'baz']);
```

[GreaterThanDateFilter.php](src/Domain/Value/Filter/Filters/GreaterThanDateFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\GreaterThanDateFilter;

new GreaterThanDateFilter(field: 'created_at', value: new \DateTimeImmutable());
```

[LessThanDateFilter.php](src/Domain/Value/Filter/Filters/LessThanDateFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\LessThanDateFilter;

new LessThanDateFilter(field: 'created_at', value: new \DateTimeImmutable());
```

[GreaterThanFloatFilter.php](src/Domain/Value/Filter/Filters/GreaterThanFloatFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\GreaterThanFloatFilter;

new GreaterThanFloatFilter(field: 'price', value: 39.99);
```

[LessThanFloatFilter.php](src/Domain/Value/Filter/Filters/LessThanFloatFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\LessThanFloatFilter;

new LessThanFloatFilter(field: 'price', value: 199.99);
```

[GreaterThanIntFilter.php](src/Domain/Value/Filter/Filters/GreaterThanIntFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\GreaterThanIntFilter;

new GreaterThanIntFilter(field: 'stock', value: 0);
```

[LessThanIntFilter.php](src/Domain/Value/Filter/Filters/LessThanIntFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\LessThanIntFilter;

new LessThanIntFilter(field: 'stock', value: 100);
```

[InFilter.php](src/Domain/Value/Filter/Filters/InFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\InFilter;

new InFilter(field: 'text', value: 'Hello World!');
// or
new InFilter(field: 'text', value: ['Hello Symfony!', 'Hello SensioLabs!']);
```

[NotInFilter.php](src/Domain/Value/Filter/Filters/NotInFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\NotInFilter;

new NotInFilter(field: 'text', value: 'Hello World!');
// or
new NotInFilter(field: 'text', value: ['Bye Symfony!', 'Bye SensioLabs!']);
```

[IsFilter.php](src/Domain/Value/Filter/Filters/IsFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\IsFilter;

// You can use one of the following constants:
// IsFilter::EMPTY_ARRAY
// IsFilter::NOT_EMPTY_ARRAY
// IsFilter::EMPTY
// IsFilter::NOT_EMPTY
// IsFilter::TRUE
// IsFilter::FALSE
// IsFilter::NULL
// IsFilter::NOT_NULL

new IsFilter(field: 'text', value: IsFilter::EMPTY);
```

[LikeFilter.php](src/Domain/Value/Filter/Filters/LikeFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\LikeFilter;

new LikeFilter(field: 'description', value: '*I love Symfony*');
```

[NotLikeFilter.php](src/Domain/Value/Filter/Filters/NotLikeFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\NotLikeFilter;

new NotLikeFilter(field: 'description', value: '*Text*');
```

[OrFilter.php](src/Domain/Value/Filter/Filters/OrFilter.php)

Example:
```php
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\OrFilter;
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\LikeFilter;
use SensioLabs\Storyblok\Api\Domain\Value\Filter\Filters\NotLikeFilter;

new OrFilter(
    new LikeFilter(field: 'text', value: 'Yes!*'),
    new LikeFilter(field: 'text', value: 'Maybe!*'),
    // ...
);
```

### Get all available stories by Content Type (`string`)

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->allByContentType('custom_content_type', locale: 'de');
```

### Get by uuid (`SensioLabs\Storyblok\Api\Domain\Value\Uuid`)

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Uuid;

$uuid = new Uuid(/** ... */);

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->byUuid($uuid, locale: 'de');
```

### Get by slug (`string`)

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->bySlug('folder/slug', locale: 'de');
```


### Get by id (`SensioLabs\Storyblok\Api\Domain\Value\Id`)

```php
use SensioLabs\Storyblok\Api\StoriesApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Id;

$id = new Id(/** ... */);

$client = new StoryblokClient(/* ... */);

$storiesApi = new StoriesApi($client);
$response = $storiesApi->byId($id, locale: 'de');
```


## Links

In your code you should type-hint to `SensioLabs\Storyblok\Api\LinksApiInterface`

### Get all available links

```php
use SensioLabs\Storyblok\Api\LinksApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$linksApi = new LinksApi($client);
$response = $linksApi->all();
```

### Pagination

```php
use SensioLabs\Storyblok\Api\LinksApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Dto\Pagination;

$client = new StoryblokClient(/* ... */);

$linksApi = new LinksApi($client);
$response = $linksApi->all(
    pagination: new Pagination(page: 1, perPage: 1000)
);
```

### Get by parent (`SensioLabs\Storyblok\Api\Domain\Value\Id`)

```php
use SensioLabs\Storyblok\Api\LinksApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Id;

$id = new Id(/** ... */);

$client = new StoryblokClient(/* ... */);

$linksApi = new LinksApi($client);
$response = $linksApi->byParent($id);
```

### Get all root links

```php
use SensioLabs\Storyblok\Api\LinksApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$linksApi = new LinksApi($client);
$response = $linksApi->roots($id);
```


## Datasource

In your code you should type-hint to `SensioLabs\Storyblok\Api\DatasourceApiInterface`

### Get by name (`string`)

```php
use SensioLabs\Storyblok\Api\DatasourceApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$api = new DatasourceApi($client);
$response = $api->byName('tags'); // returns SensioLabs\Storyblok\Api\Domain\Value\Datasource
```

If it has more than one dimension, you can get the entries by

```php
use SensioLabs\Storyblok\Api\DatasourceApi;
use SensioLabs\Storyblok\Api\StoryblokClient;
use SensioLabs\Storyblok\Api\Domain\Value\Datasource\Dimension;

$client = new StoryblokClient(/* ... */);

$api = new DatasourceApi($client);
$response = $api->byName('tags', new Dimension('de')); // returns SensioLabs\Storyblok\Api\Domain\Value\Datasource
```

## Tags

In your code you should type-hint to `SensioLabs\Storyblok\Api\TagsApiInterface`

### Get all available tags

```php
use SensioLabs\Storyblok\Api\TagsApi;
use SensioLabs\Storyblok\Api\StoryblokClient;

$client = new StoryblokClient(/* ... */);

$api = new TagsApi($client);
$response = $api->all(); // returns SensioLabs\Storyblok\Api\Response\TagsResponse
```

[actions]: https://github.com/sensiolabs-de/storyblok-api/actions
[codecov]: https://codecov.io/gh/sensiolabs-de/storyblok-api
