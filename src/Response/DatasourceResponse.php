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

namespace SensioLabs\Storyblok\Api\Response;

use SensioLabs\Storyblok\Api\Domain\Value\Datasource\Entry;
use Webmozart\Assert\Assert;

final readonly class DatasourceResponse
{
    /**
     * @var list<Entry>
     */
    public array $entries;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values)
    {
        Assert::keyExists($values, 'datasource_entries');
        Assert::isArray($values['datasource_entries']);

        $entries = [];

        if ([] !== $values['datasource_entries']) {
            foreach ($values['datasource_entries'] as $entry) {
                Assert::isArray($entry);
                $entries[] = new Entry($entry);
            }
        }

        $this->entries = $entries;
    }
}