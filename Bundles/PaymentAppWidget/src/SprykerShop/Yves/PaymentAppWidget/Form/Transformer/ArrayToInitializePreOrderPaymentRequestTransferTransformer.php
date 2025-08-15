<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Form\Transformer;

use Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface
 */
class ArrayToInitializePreOrderPaymentRequestTransferTransformer implements DataTransformerInterface
{
    /**
     * @param array<mixed>|null $value
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return \Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer
     */
    public function transform(mixed $value): InitializePreOrderPaymentRequestTransfer
    {
        if ($value === null) {
            return new InitializePreOrderPaymentRequestTransfer();
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return (new InitializePreOrderPaymentRequestTransfer())->fromArray($value);
    }

    /**
     * @param \Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer|null $value
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return \Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer
     */
    public function reverseTransform(mixed $value): InitializePreOrderPaymentRequestTransfer
    {
        if ($value === null) {
            return new InitializePreOrderPaymentRequestTransfer();
        }

        if (!$value instanceof InitializePreOrderPaymentRequestTransfer) {
            throw new TransformationFailedException('Expected an instance of InitializePreOrderPaymentRequestTransfer.');
        }

        return $value;
    }
}
