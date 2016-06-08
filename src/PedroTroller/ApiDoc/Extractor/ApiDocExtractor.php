<?php

namespace PedroTroller\ApiDoc\Extractor;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\ApiDocExtractor as BaseExtractor;
use PedroTroller\ApiDoc\Route\DescriptionBuilder;
use Symfony\Component\Routing\Route;

class ApiDocExtractor extends BaseExtractor
{
    /**
     * @param DescriptionBuilder $descriptionBuilder
     *
     * @return ApiDocExtractor
     */
    public function setRouteDescriptionBuilder(DescriptionBuilder $descriptionBuilder)
    {
        $this->descriptionBuilder = $descriptionBuilder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function extractData(ApiDoc $annotation, Route $route, \ReflectionMethod $method)
    {
        $annotation = parent::extractData($annotation, $route, $method);

        $this->completeParameters($annotation, $route);
        $this->completeResponses($annotation, $route);

        return $annotation;
    }

    /**
     * @param ApiDoc $annotation
     * @param Route  $route
     */
    private function completeParameters(ApiDoc $annotation, Route $route)
    {
        foreach ($annotation->getParameters() as $key => $parameter) {
            $annotation->addParameter($key, $this->completeParameter($key, $parameter, $route));
        }
    }

    /**
     * @param string $key
     * @param array  $config
     * @param Route  $route
     *
     * @return array
     */
    private function completeParameter($key, array $config, Route $route)
    {
        if (empty($config['description'])) {
            if (array_key_exists('children', $config)) {
                $config['description'] = $this->descriptionBuilder->buildDescription($route, sprintf('parameters.%s.0', $key));
            } else {
                $config['description'] = $this->descriptionBuilder->buildDescription($route, sprintf('parameters.%s', $key));
            }
        }

        if (array_key_exists('format', $config)) {
            $format = $config['format'];
            if (false !== $data = json_decode($format)) {
                $data = $data instanceof \stdClass ? get_object_vars($data) : $data;
                if (100 < count($data)) {
                    $config['format'] = sprintf('[ %s ]', implode(', ', array_keys($data)));
                }
            }
        }

        if (array_key_exists('children', $config)) {
            foreach ($config['children'] as $childkey => $childconfig) {
                $config['children'][$childkey] = $this->completeParameter(sprintf('%s.%s', $key, $childkey), $childconfig, $route);
            }
        }

        return $config;
    }

    /**
     * @param ApiDoc $annotation
     * @param Route  $route
     *
     * @return array
     */
    private function completeResponses(ApiDoc $annotation, Route $route)
    {
        $data = $annotation->toArray();

        if (false === array_key_exists('response', $data)) {
            return;
        }

        foreach ($data['response'] as $key => $item) {
            $data['response'][$key] = $this->completeResponse($key, $item, $route);
        }

        $annotation->setResponse($data['response']);
    }

    /**
     * @param string $key
     * @param array  $config
     * @param Route  $route
     *
     * @return array
     */
    private function completeResponse($key, array $config, Route $route)
    {
        $key = str_replace('..', '.', ltrim($key, '.'));

        if (empty($config['description'])) {
            if (empty($key)) {
                $config['description'] = $this->descriptionBuilder->buildDescription($route, sprintf('response.0', $key));
            } elseif (array_key_exists('children', $config)) {
                $config['description'] = $this->descriptionBuilder->buildDescription($route, sprintf('response.%s.0', $key));
            } else {
                $config['description'] = $this->descriptionBuilder->buildDescription($route, sprintf('response.%s', $key));
            }
        }

        if (array_key_exists('children', $config)) {
            foreach ($config['children'] as $childkey => $childconfig) {
                $config['children'][$childkey] = $this->completeResponse(sprintf('%s.%s', $key, $childkey), $childconfig, $route);
            }
        }

        return $config;
    }
}
