<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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
        $constraint = new Collection(
            $this->getQueryValidations(),
        );

        return $this->validator->validate($query, $constraint);
    }

    /**
     * @return array<string, array<\Symfony\Component\Validator\Constraint>>
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
}
