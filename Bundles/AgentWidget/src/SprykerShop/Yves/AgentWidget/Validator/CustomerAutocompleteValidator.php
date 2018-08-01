<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Validator;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerAutocompleteValidator implements CustomerAutocompleteValidatorInterface
{
    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $query
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    public function validate(array $query): ConstraintViolationListInterface
    {
        $constraint = new Collection(array_merge(
            $this->getQueryValidations(),
            $this->getLimitValidations()
        ));

        return $this->validator->validate($query, $constraint);
    }

    /**
     * @return array
     */
    protected function getQueryValidations(): array
    {
        return [
            'query' => [
                new NotBlank(),
                new Length(['min' => 3]),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getLimitValidations(): array
    {
        return [
            'limit' => [
                new Type('numeric'),
                new Choice([5, 10, 15]),
            ],
        ];
    }
}
