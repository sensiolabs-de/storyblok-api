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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SensioLabs\Storyblok\Api\Bridge\HttpClient\QueryStringHelper;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpClientTrait;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

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
        private LoggerInterface $logger = new NullLogger(),
    ) {
        $this->client = $storyblokClient ?? HttpClient::createForBaseUri($baseUri);
        $this->token = TrimmedNonEmptyString::fromString($token, '$token must not be an empty string')->toString();
        $this->timeout = $timeout;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        Assert::notStartsWith($url, 'http', '$url should be relative: Got: %s');
        Assert::startsWith($url, '/', '$url should start with a "/". Got: %s');

        $options['timeout'] ??= $this->timeout;

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
            $url = QueryStringHelper::applyQueryString($url, [
                ...$options['query'],
                'token' => $this->token,
            ]);
            unset($options['query']);
        } else {
            $options['query'] = [
                'token' => $this->token,
            ];
        }

        try {
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
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            throw $e;
        }
    }
}
