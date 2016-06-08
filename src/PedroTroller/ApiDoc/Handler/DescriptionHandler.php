<?php

namespace PedroTroller\ApiDoc\Handler;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\HandlerInterface;
use PedroTroller\ApiDoc\Route\DescriptionBuilder;
use Symfony\Component\Routing\Route;

class DescriptionHandler implements HandlerInterface
{
    /**
     * @var DescriptionBuilder
     */
    private $descriptionBuilder;

    /**
     * @param DescriptionBuilder $descriptionBuilder
     */
    public function __construct(DescriptionBuilder $descriptionBuilder)
    {
        $this->descriptionBuilder = $descriptionBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ApiDoc $annotation, array $annotations, Route $route, \ReflectionMethod $method)
    {
        if (null !== $annotation->getDescription() && 0 < strlen($annotation->getDescription())) {
            return;
        }

        $description = $this->descriptionBuilder->buildDescription($route);
        $annotation->setDescription($description);
    }
}
