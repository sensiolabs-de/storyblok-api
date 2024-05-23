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

use OskarStark\Value\TrimmedNonEmptyString;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpClientTrait;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;
use function Safe\preg_replace;

/**
 * @author Silas Joisten <silasjoisten@proton.me>
 */
final class StoryblokClient implements StoryblokClientInterface
{
    use HttpClientTrait;

    private HttpClientInterface $client;
    private string $token;
    private int $timeout;

    public function __construct(
        string $baseUri,
        #[\SensitiveParameter]
        string $token,
        int $timeout = 4,
        ?HttpClientInterface $storyblokClient = null,
    ) {
        $this->client = $storyblokClient ?? HttpClient::createForBaseUri($baseUri);
        $this->token = TrimmedNonEmptyString::fromString($token, '$token must not be an empty string')->toString();
        $this->timeout = $timeout;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        Assert::notStartsWith($url, 'http', '$url should be relative: Got: %s');
        Assert::startsWith($url, '/', '$url should start with a "/". Got: %s');

        if (!\array_key_exists('timeout', $options)) {
            $options['timeout'] = $this->timeout;
        }

        /*
         * This workaround is necessary because the symfony/http-client does not support URL array syntax like in JavaScript.
         * Specifically, this issue arises with the "OrFilter" query parameter, which needs to be formatted as follows:
         * query_filter[__or][][field][filter]=value
         *
         * The default behavior of the Http Client includes the array key in the query string, causing a 500 error on the Storyblok API side.
         * Instead of generating the required format, the symfony/http-client generates a query string that looks like:
         * query_filter[__or][0][field][filter]=value&query_filter[__or][1][field][filter]=value
         */
        if (\array_key_exists('query', $options)) {
            $query = $options['query'] + ['token' => $this->token];
            unset($options['query']);

            $query = $this->mergeQueryString('', $query, true);
            Assert::string($query);

            $pattern = '/\[__or]\[(\d+)]/';
            $query = preg_replace($pattern, '[__or][]', $query);

            if (str_contains($url, '?')) {
                $url .= '&'.$query;
            } else {
                $url .= '?'.$query;
            }
        }

        return $this->client->request(
            $method,
            $url,
            array_merge_recursive(
                $options,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                ],
            ),
        );
    }
}
