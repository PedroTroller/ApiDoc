<?php

namespace PedroTroller\ApiDoc\Route;

use Symfony\Component\Routing\Route;
use Symfony\Component\Translation\TranslatorInterface;

class DescriptionBuilder
{
    /**
     * @var NameFinder
     */
    private $nameFinder;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param NameFinder          $nameFinder
     * @param TranslatorInterface $translator
     */
    public function __construct(NameFinder $nameFinder, TranslatorInterface $translator)
    {
        $this->nameFinder = $nameFinder;
        $this->translator = $translator;
    }

    /**
     * @param Route  $route
     * @param string $part
     *
     * @return string
     */
    public function buildDescription(Route $route, $part = 'description')
    {
        $name  = $this->nameFinder->findRouteName($route);
        $key   = sprintf('%s.%s', $name, $part);
        $trans = $this->translator->trans($key, array(), 'nelmio');

        if (false === empty($trans)) {
            $trans = sprintf('%s.', trim($trans, '.'));
        }

        return $trans;
    }
}
