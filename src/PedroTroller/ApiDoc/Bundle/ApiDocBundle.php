<?php

namespace PedroTroller\ApiDoc\Bundle;

use Nelmio\ApiDocBundle\DependencyInjection\ExtractorHandlerCompilerPass;
use PedroTroller\ApiDoc\DependencyInjection\ApiDocExtension;
use PedroTroller\ApiDoc\DependencyInjection\Compiler\NelmioPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiDocBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new NelmioPass());
        $container->addCompilerPass(new ExtractorHandlerCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ApiDocExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'NelmioApiDocBundle';
    }
}
