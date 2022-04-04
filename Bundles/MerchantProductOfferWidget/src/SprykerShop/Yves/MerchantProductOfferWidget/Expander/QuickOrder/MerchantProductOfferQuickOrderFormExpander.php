<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder;

use Generated\Shared\Transfer\ProductOfferTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Form\DataProvider\MerchantProductOffersSelectFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MerchantProductOfferQuickOrderFormExpander implements MerchantProductOfferQuickOrderFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Form\DataProvider\MerchantProductOffersSelectFormDataProvider
     */
    protected $merchantProductOffersSelectFormDataProvider;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Form\DataProvider\MerchantProductOffersSelectFormDataProvider $merchantProductOffersSelectFormDataProvider
     */
    public function __construct(MerchantProductOffersSelectFormDataProvider $merchantProductOffersSelectFormDataProvider)
    {
        $this->merchantProductOffersSelectFormDataProvider = $merchantProductOffersSelectFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();
            $quickOrderItemTransfer = $event->getData();

            if (!$quickOrderItemTransfer || !$quickOrderItemTransfer->getSku()) {
                $form->add(static::FIELD_PRODUCT_OFFER_REFERENCE, HiddenType::class);

                return;
            }

            $choices = $this->merchantProductOffersSelectFormDataProvider->getOptions(
                $quickOrderItemTransfer->getSku(),
                $quickOrderItemTransfer->getMerchantReference(),
            );

            if ($choices && count($choices) === 1) {
                $choice = reset($choices);

                $quickOrderItemTransfer->setProductOfferReference(
                    $choice->getProductOfferReference(),
                );

                $event->setData($quickOrderItemTransfer);
            }

            $selectedData = $this->getSelectedProductOfferTransfer(
                $choices,
                $quickOrderItemTransfer->getProductOfferReference(),
            );

            $form->add(
                static::FIELD_PRODUCT_OFFER_REFERENCE,
                ChoiceType::class,
                [
                    'choices' => $choices,
                    'choice_label' => ProductOfferTransfer::MERCHANT_NAME,
                    'choice_value' => ProductOfferTransfer::PRODUCT_OFFER_REFERENCE,
                    'data' => $selectedData,
                ],
            );
        });

        return $builder;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $choices
     * @param string|null $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    protected function getSelectedProductOfferTransfer(array $choices, ?string $productOfferReference): ?ProductOfferTransfer
    {
        foreach ($choices as $productOfferTransfer) {
            if ($productOfferTransfer->getProductOfferReference() === $productOfferReference) {
                return $productOfferTransfer;
            }
        }

        return null;
    }
}
