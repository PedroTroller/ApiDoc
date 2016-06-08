<?php

namespace PedroTroller\ApiDoc\Route;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class NameFinder
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Route $route
     *
     * @throw \Exception
     *
     * @return string
     */
    public function findRouteName(Route $route)
    {
        foreach ($this->router->getRouteCollection() as $name => $current) {
            if ($route === $current) {
                return $name;
            }
        }
        throw new \Exception('Can\'t find route name.');
    }
}
