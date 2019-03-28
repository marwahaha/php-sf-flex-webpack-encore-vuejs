<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientController extends AbstractController
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * HttpClientController constructor.
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @Cache(expires="+24 hour")
     * @Route(
     *     "/demo/http",
     *     methods={"GET"}
     *     )
     */
    public function call()
    {
        $response = $this->httpClient->request('GET', 'https://ghibliapi.herokuapp.com/films');

        if ($response->getStatusCode() > 299) {
            throw new HttpException("500", sprintf("Api ghibli returned bad response, status is %d", $response->getStatusCode()));
        }

        return new JsonResponse($response->getContent(), 200, [], true);
    }
}
