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

use SensioLabs\Storyblok\Api\Domain\Value\Datasource\Datasource;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
interface DatasourceApiInterface
{
    public function byName(string $name): Datasource;
}
