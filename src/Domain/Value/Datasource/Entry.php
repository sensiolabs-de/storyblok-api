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

namespace SensioLabs\Storyblok\Api\Domain\Value\Datasource;

use OskarStark\Value\TrimmedNonEmptyString;
use SensioLabs\Storyblok\Api\Domain\Value\Id;
use Webmozart\Assert\Assert;

final readonly class Entry
{
    public Id $id;
    public string $name;
    public string $value;
    public string $dimensionValue;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values)
    {
        Assert::keyExists($values, 'id');
        Assert::integer($values['id']);
        $this->id = new Id($values['id']);

        Assert::keyExists($values, 'name');
        $this->name = TrimmedNonEmptyString::fromString($values['name'])->toString();

        Assert::keyExists($values, 'value');
        $this->value = TrimmedNonEmptyString::fromString($values['value'])->toString();

        Assert::keyExists($values, 'dimension_value');
        $this->dimensionValue = TrimmedNonEmptyString::fromString($values['dimension_value'])->toString();
    }
}