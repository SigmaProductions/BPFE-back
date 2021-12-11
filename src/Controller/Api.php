<?php
declare(strict_types=1);

namespace App\Controller;

use App\Bik\RepositoryAggregate;
use App\DTO\Address;
use chan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Api extends AbstractController
{
    public function __construct(
        private RepositoryAggregate $repositoryAggregate
    ) {

    }

    public function aggregate(Request $request): JsonResponse
    {
        $decoded = json_decode($request->getContent(), true);
        var_dump($decoded);
        $address = new Address($decoded['address']['street'], $decoded['address']['number'], $decoded['address']['postcode']);
        $demanded = $decoded['demanded'];
        $result = [];

        $chan = new chan(count($demanded));

        foreach ($demanded as $single) {
            if (str_contains($single, '/')) {
                [$primary, $secondary] = explode('/', $single);
                go(fn () => $chan->push($this->repositoryAggregate->get($address, $primary, $secondary)));
            } else {
                go(fn () => $chan->push($this->repositoryAggregate->get($address, $single)));
            }
        }

        for ($i = 0; $i < count($demanded); $i++)
        {
            $result = array_merge($result, $chan->pop());
        }

        return new JsonResponse(
            $result
        );
    }
}