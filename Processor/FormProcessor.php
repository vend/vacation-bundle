<?php

namespace Vend\Bundle\VacationBundle\Processor;

use Vend\Vacation\Metadata\Operation;
use Vend\Vacation\Processor\ConfigurableProcessor;
use Vend\Vacation\Request\RequestInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormProcessor extends ConfigurableProcessor
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('form')
            ->children()
                ->scalarNode('factory')->isRequired()->end()
                ->booleanNode('validate')->defaultTrue()->end()
                ->scalarNode('property')->defaultValue(null)->end()
                ->booleanNode('clearMissing')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @param object           $controller
     * @param Operation        $operationMetadata
     * @param RequestInterface $request
     * @return mixed
     */
    protected function doProcess($controller, Operation $operationMetadata, RequestInterface $request)
    {
        $arguments = [
            $request->getQueryParameters(),
        ];

        /** @var FormInterface $form */
        $form = call_user_func_array([$controller, $this->config['factory']], $arguments);

        $payload = $request->getPayloadAsArray();

        if (null !== ($property = $this->config['property'])) {
            $payload = empty($payload[$property]) ? [] : $payload[$property];
        }

        $form->submit($payload, $this->config['clearMissing']);

        if ($this->config['validate'] && !$form->isValid()) {
            return $form->getErrors();
        }

        $arguments = [
            $form
        ];

        return $operationMetadata->invoke($controller, $arguments);
    }
}
