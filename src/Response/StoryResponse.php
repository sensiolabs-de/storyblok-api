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

use SensioLabs\Storyblok\Api\Domain\Value\Story;
use Webmozart\Assert\Assert;

final readonly class StoryResponse
{
    public Story $story;
    public int $cv;

    /**
     * @var list<array<mixed>>
     */
    public array $rels;

    /**
     * @var list<array<mixed>>
     */
    public array $links;

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(array $values)
    {
        Assert::keyExists($values, 'story');
        $this->story = Story::fromArray($values['story']);

        Assert::keyExists($values, 'cv');
        Assert::integer($values['cv']);
        $this->cv = $values['cv'];

        Assert::keyExists($values, 'rels');
        $this->rels = $values['rels'];

        Assert::keyExists($values, 'links');
        $this->links = $values['links'];
    }
}
