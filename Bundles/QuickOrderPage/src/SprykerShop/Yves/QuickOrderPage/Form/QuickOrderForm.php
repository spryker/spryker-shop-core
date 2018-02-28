<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderForm extends AbstractType
{
    public const FIELD_ITEMS = 'items';
    public const FIELD_TEXT_ORDER = 'textOrder';

    public const SUBMIT_BUTTON_ADD_TO_CART = 'addToCart';
    public const SUBMIT_BUTTON_CREATE_ORDER = 'createOrder';
    public const SUBMIT_BUTTON_VERIFY = 'verifyTextOrder';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addItemsCollection($builder)
            ->addTextOrderField($builder)
            ->addPreSetData($builder)
            ->addPrePreSubmitData($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderData::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemsCollection(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FIELD_ITEMS, CollectionType::class, [
            'entry_type' => OrderItemEmbeddedForm::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTextOrderField(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FIELD_TEXT_ORDER, TextareaType::class, [
            'required' => false,
            'constraints' => [
                new Callback([
                    'callback' => function (string $textOrder, ExecutionContextInterface $context) {
                        if ($textOrder) {
                            $isTextOrderFormatCorrect = $this->getFactory()
                                ->createTextOrderValidator()
                                ->checkFormat($textOrder);

                            if (!$isTextOrderFormatCorrect) {
                                $context->buildViolation('Order format is incorrect.')->addViolation();
                            }
                        }
                    },
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPreSetData(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                if ($event->getData() === null) {
                    $quickOrder = new QuickOrderData();
                    $productRowsNumber = $this->getFactory()->getBundleConfig()->getProductRowsNumber();
                    $orderItems = [];

                    for ($i = 0; $i < $productRowsNumber; $i++) {
                        $orderItems[] = new QuickOrderItemTransfer();
                    }

                    $quickOrder->setItems($orderItems);
                    $event->setData($quickOrder);
                }
            }
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPrePreSubmitData(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $textOrder = $data[self::FIELD_TEXT_ORDER];

                $isTextOrderFormatCorrect = $this->getFactory()
                    ->createTextOrderValidator()
                    ->checkFormat($textOrder);

                if ($isTextOrderFormatCorrect) {
                    $parsedItems = $this->getFactory()
                        ->createTextOrderParser($textOrder)
                        ->getOrderItems();

                    if ($parsedItems) {
                        $parsedItems = array_map(function (QuickOrderItemTransfer $quickOrderItemTransfer) {
                            return [
                                OrderItemEmbeddedForm::FILED_SEARCH_FIELD => 'name_sku',
                                OrderItemEmbeddedForm::FILED_SEARCH_QUERY => $quickOrderItemTransfer->getSearchQuery(),
                                OrderItemEmbeddedForm::FILED_SKU => $quickOrderItemTransfer->getSku(),
                                OrderItemEmbeddedForm::FILED_QTY => $quickOrderItemTransfer->getQty(),
                                OrderItemEmbeddedForm::FILED_PRICE => $quickOrderItemTransfer->getPrice(),
                            ];
                        }, $parsedItems);

                        $data[self::FIELD_ITEMS] = $parsedItems;
                        $event->setData($data);
                    }
                }
            }
        );

        return $this;
    }
}
