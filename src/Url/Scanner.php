<?php

namespace Skywalker\Scanner\Url;

class Scanner
{
    /**
     * @var array
     */
    private $urls;

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * Scanner constructor.
     *
     * @param \GuzzleHttp\Client $httpClient
     * @param array $urls
     */
    public function __construct(\GuzzleHttp\Client $httpClient, array $urls)
    {
        $this->httpClient = $httpClient;
        $this->urls = $urls;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getUrlsStatusCode($url);
            } catch (\Exception $exception) {
                $statusCode = 500;
            }
            if ($statusCode >= 400) {
                $invalidUrls[] = ['url' => $url, 'status' => $statusCode];
            }
        }
        return $invalidUrls;
    }

    /**
     * @param $url
     *
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getUrlsStatusCode($url)
    {
        $response = $this->httpClient->request('GET', $url);
        return $response->getStatusCode();
    }
}
