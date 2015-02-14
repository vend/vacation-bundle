<?php

namespace Vend\Bundle\VacationBundle\Validation;

use Vend\Vacation\Metadata\Operation;
use Vend\Vacation\Operation\ArgumentBag;
use Vend\Vacation\Request\RequestInterface;
use Vend\Vacation\Validation\ValidationPassInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormPass implements ValidationPassInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param RequestInterface $request
     * @param object           $controller
     * @param Operation        $operationMetadata
     * @param ArgumentBag      $operationArguments
     * @return FormErrorIterator|bool
     */
    public function validate(RequestInterface $request, $controller, Operation $operationMetadata, ArgumentBag $operationArguments)
    {
        if (!$operationMetadata->formFactory) {
            return true;
        }

        $formFactory = $operationMetadata->formFactory;

        $formFactoryArguments = [
            $request->getAttributes(),
            $request->getQueryParameters()
        ];

        /** @var FormInterface $form */
        $form = call_user_func_array([$controller, $formFactory], $formFactoryArguments);

        $form->submit($request->getPayloadAsArray());

        if (!$form->isValid()) {
            return $form->getErrors(true);
        }

        $operationArguments->addArgument($form);

        return true;
    }
}
