<?php

namespace PedroTroller\ApiDoc\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NelmioPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $extractor = $container->getDefinition('nelmio_api_doc.extractor.api_doc_extractor');
        $extractor->addMethodCall('setRouteDescriptionBuilder', array(new Reference('api_doc.route.description_builder')));
    }
}
