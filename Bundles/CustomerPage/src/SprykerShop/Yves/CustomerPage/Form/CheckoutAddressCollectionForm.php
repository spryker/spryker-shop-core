<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToShipmentServiceInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\IsFalse;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CheckoutAddressCollectionForm extends AbstractType
{
    public const FIELD_SHIPPING_ADDRESS = 'shippingAddress';
    public const FIELD_BILLING_ADDRESS = 'billingAddress';
    public const FIELD_BILLING_SAME_AS_SHIPPING = 'billingSameAsShipping';
    public const FIELD_MULTI_SHIPPING_ADDRESSES = 'multiShippingAddresses';
    public const FIELD_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS = 'multiShippingAddressesForBundleItems';
    public const FIELD_IS_MULTIPLE_SHIPMENT_ENABLED = 'isMultipleShipmentEnabled';

    public const OPTION_ADDRESS_CHOICES = 'address_choices';
    public const OPTION_COUNTRY_CHOICES = 'country_choices';
    public const OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES = 'can_deliver_to_multiple_shipping_addresses';
    public const OPTION_IS_CUSTOMER_LOGGED_IN = 'is_customer_logged_in';
    public const OPTION_BUNDLE_ITEMS = 'bundleItems';

    public const GROUP_SHIPPING_ADDRESS = self::FIELD_SHIPPING_ADDRESS;
    public const GROUP_BILLING_ADDRESS = self::FIELD_BILLING_ADDRESS;
    public const GROUP_BILLING_SAME_AS_SHIPPING = self::FIELD_BILLING_SAME_AS_SHIPPING;

    public const VALIDATION_BILLING_SAME_AS_SHIPPING_MESSAGE = 'Billing address should not be specified when shipping to multiple addresses.';

    protected const GLOSSARY_KEY_ADD_NEW_ADDRESS = 'customer.address.add_new_address';
    protected const GLOSSARY_KEY_SAVE_NEW_ADDRESS = 'customer.address.save_new_address';
    protected const GLOSSARY_KEY_DELIVER_TO_MULTIPLE_ADDRESSES = 'customer.account.deliver_to_multiple_addresses';

    protected const PROPERTY_PATH_MULTI_SHIPPING_ADDRESSES = 'items';
    protected const PROPERTY_PATH_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS = 'bundleItems';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'addressesForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /** @var \Symfony\Component\OptionsResolver\OptionsResolver $resolver */
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP, static::GROUP_SHIPPING_ADDRESS];

                if (!$form->get(static::FIELD_BILLING_SAME_AS_SHIPPING)->getData()) {
                    $validationGroups[] = static::GROUP_BILLING_ADDRESS;
                }

                return $validationGroups;
            },
            static::OPTION_ADDRESS_CHOICES => [],
        ]);

        $resolver->setDefined(static::OPTION_ADDRESS_CHOICES)
            ->setRequired(static::OPTION_COUNTRY_CHOICES)
            ->setRequired(static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES)
            ->setRequired(static::OPTION_IS_CUSTOMER_LOGGED_IN)
            ->setRequired(static::OPTION_BUNDLE_ITEMS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addShippingAddressSubForm($builder, $options)
            ->addItemShippingAddressSubForm($builder, $options)
            ->addItemShippingAddressForBundlesSubForm($builder, $options)
            ->addSameAsShippingCheckboxField($builder)
            ->addBillingAddressSubForm($builder, $options)
            ->addIsMultipleShipmentEnabledField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShippingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        $shipmentService = $this->getFactory()->getShipmentService();

        $fieldOptions = [
            'data_class' => AddressTransfer::class,
            'required' => true,
            'mapped' => false,
            'validation_groups' => function (FormInterface $form) {
                if ($this->isIdCustomerAddressFieldNotEmpty($form)
                    || $this->isIdCompanyUnitAddressFieldNotEmpty($form)
                ) {
                    return false;
                }

                return [static::GROUP_SHIPPING_ADDRESS];
            },
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => static::GROUP_SHIPPING_ADDRESS,
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $this->getShippingAddressChoices($options),
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
            CheckoutAddressForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
        ];

        $builder->add(static::FIELD_SHIPPING_ADDRESS, CheckoutAddressForm::class, $fieldOptions);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($shipmentService) {
            $this->hydrateShippingAddressSubFormDataFromItemLevelShippingAddresses($event, $shipmentService);
            $this->hydrateShippingAddressSubFormDataFromBundleItemLevelShippingAddresses($event, $shipmentService);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $event = $this->mapSubmittedShippingAddressSubFormDataToItemLevelShippingAddresses($event);
            $event = $this->mapSubmittedShippingAddressSubFormDataToBundleItemLevelShippingAddresses($event);
            $event = $this->copyBundleItemLevelShippingAddressesToItemLevelShippingAddresses($event, $options);

            return $event;
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    protected function copyBundleItemLevelShippingAddressesToItemLevelShippingAddresses(FormEvent $event, array $options): FormEvent
    {
        // TODO: refactor it
        $quoteTransfer = $event->getData();
        if (!($quoteTransfer instanceof QuoteTransfer)) {
            return $event;
        }

        $bundleItems = [];

        foreach ($options[static::OPTION_BUNDLE_ITEMS] as $bundleItem) {
            $bundleItems[$bundleItem->getGroupKey()] = $bundleItem->getShipment();
        }

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (isset($bundleItems[$itemTransfer->getGroupKey()])) {
                $itemTransfer->setShipment($bundleItems[$itemTransfer->getGroupKey()]);
            }
        }

        $relatedBundleItemIdentifiers = [];

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $relatedBundleItemIdentifiers[$itemTransfer->getBundleItemIdentifier()] = $itemTransfer->getShipment();
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() && isset($relatedBundleItemIdentifiers[$itemTransfer->getRelatedBundleItemIdentifier()])) {
                $itemTransfer->setShipment($relatedBundleItemIdentifiers[$itemTransfer->getRelatedBundleItemIdentifier()]);
            }
        }

        $event->setData($quoteTransfer);

        return $event;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToShipmentServiceInterface $shipmentService
     *
     * @return void
     */
    protected function hydrateShippingAddressSubFormDataFromItemLevelShippingAddresses(
        FormEvent $event,
        CustomerPageToShipmentServiceInterface $shipmentService
    ): void {
        $quoteTransfer = $event->getData();
        if (!($quoteTransfer instanceof QuoteTransfer)) {
            return;
        }

        $shipmentGroupCollection = $shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $form = $event->getForm();
        $shippingAddressFrom = $form->get(static::FIELD_SHIPPING_ADDRESS);

        if ($shipmentGroupCollection->count() > 1) {
            $shippingAddressFrom = $this->setDeliverToMultipleAddressesEnabled($shippingAddressFrom);
        }

        if ($this->isDeliverToMultipleAddressesEnabled($shippingAddressFrom)) {
            return;
        }

        if ($shipmentGroupCollection->count() < 1) {
            return;
        }

        /** @var \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer */
        $shipmentGroupTransfer = $shipmentGroupCollection->getIterator()->current();

        if ($shipmentGroupTransfer === null) {
            return;
        }

        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return;
        }

        $shippingAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shippingAddressTransfer === null) {
            return;
        }

        $shippingAddressFrom->setData(clone $shippingAddressTransfer);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToShipmentServiceInterface $shipmentService
     *
     * @return void
     */
    protected function hydrateShippingAddressSubFormDataFromBundleItemLevelShippingAddresses(
        FormEvent $event,
        CustomerPageToShipmentServiceInterface $shipmentService
    ): void {
        $quoteTransfer = $event->getData();
        if (!($quoteTransfer instanceof QuoteTransfer)) {
            return;
        }

        $shipmentGroupCollection = $shipmentService->groupItemsByShipment($quoteTransfer->getBundleItems());
        $form = $event->getForm();
        $shippingAddressFrom = $form->get(static::FIELD_SHIPPING_ADDRESS);

        if ($shipmentGroupCollection->count() > 1) {
            $shippingAddressFrom = $this->setDeliverToMultipleAddressesEnabled($shippingAddressFrom);
        }

        if ($this->isDeliverToMultipleAddressesEnabled($shippingAddressFrom)) {
            return;
        }

        if ($shipmentGroupCollection->count() < 1) {
            return;
        }

        /** @var \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer */
        $shipmentGroupTransfer = $shipmentGroupCollection->getIterator()->current();

        if ($shipmentGroupTransfer === null) {
            return;
        }

        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return;
        }

        $shippingAddressTransfer = $shipmentTransfer->getShippingAddress();
        if ($shippingAddressTransfer === null) {
            return;
        }

        $shippingAddressFrom->setData(clone $shippingAddressTransfer);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    protected function mapSubmittedShippingAddressSubFormDataToItemLevelShippingAddresses(FormEvent $event): FormEvent
    {
        $quoteTransfer = $event->getData();
        if (!($quoteTransfer instanceof QuoteTransfer)) {
            return $event;
        }

        $form = $event->getForm();
        $shippingAddressFrom = $form->get(static::FIELD_SHIPPING_ADDRESS);
        if ($this->isDeliverToMultipleAddressesEnabled($shippingAddressFrom)) {
            return $event;
        }

        $shippingAddressTransfer = $shippingAddressFrom->getData();
        $shipmentTransfer = $this->getQuoteItemShipmentTransfer($quoteTransfer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        $event->setData($quoteTransfer);

        return $event;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    protected function mapSubmittedShippingAddressSubFormDataToBundleItemLevelShippingAddresses(FormEvent $event): FormEvent
    {
        $quoteTransfer = $event->getData();
        if (!($quoteTransfer instanceof QuoteTransfer)) {
            return $event;
        }

        $form = $event->getForm();
        $shippingAddressFrom = $form->get(static::FIELD_SHIPPING_ADDRESS);
        if ($this->isDeliverToMultipleAddressesEnabled($shippingAddressFrom)) {
            return $event;
        }

        $shippingAddressTransfer = $shippingAddressFrom->getData();
        $shipmentTransfer = $this->getQuoteItemShipmentTransfer($quoteTransfer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        $event->setData($quoteTransfer);

        return $event;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getQuoteItemShipmentTransfer(QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        $itemTransfer = $quoteTransfer->getItems()
            ->getIterator()
            ->current();

        if ($itemTransfer !== null && $itemTransfer->getShipment()) {
            return $itemTransfer->getShipment();
        }

        return new ShipmentTransfer();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function setDeliverToMultipleAddressesEnabled(FormInterface $form): FormInterface
    {
        if (!$form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)) {
            return $form;
        }

        $selectedDeliverToMultiShipmentAddressTransfer = (new AddressTransfer())
            ->setIdCustomerAddress(CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES);

        $form->setData($selectedDeliverToMultiShipmentAddressTransfer);

        return $form;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isDeliverToMultipleAddressesEnabled(FormInterface $form): bool
    {
        if (!$form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)) {
            return false;
        }

        $idCustomerAddress = $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getViewData();

        return $idCustomerAddress === CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSameAsShippingCheckboxField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BILLING_SAME_AS_SHIPPING,
            CheckboxType::class,
            [
                'required' => false,
                'constraints' => [
                    $this->createBillingSameAsShippingConstraint(),
                ],
                'validation_groups' => function (FormInterface $form) {
                    $shippingAddressForm = $form->getParent()
                        ? $form->getParent()->get(static::FIELD_SHIPPING_ADDRESS)
                        : null;

                    if (!$shippingAddressForm) {
                        return false;
                    }

                    if (!$this->isDeliverToMultipleAddressesEnabled($shippingAddressForm)) {
                        return false;
                    }

                    return [static::GROUP_BILLING_SAME_AS_SHIPPING];
                },
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addBillingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        $options = [
            'data_class' => AddressTransfer::class,
            'validation_groups' => function (FormInterface $form) {
                if ($form->getParent()->get(static::FIELD_BILLING_SAME_AS_SHIPPING)->getData()) {
                    return false;
                }

                if ($this->isIdCustomerAddressExistAndNotEmpty($form)
                    || $this->isIdCompanyUnitAddressFieldExistAndNotEmpty($form)
                ) {
                    return false;
                }

                return [static::GROUP_BILLING_ADDRESS];
            },
            'required' => true,
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => static::GROUP_BILLING_ADDRESS,
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $options[static::OPTION_ADDRESS_CHOICES],
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
            CheckoutAddressForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
        ];

        $builder->add(static::FIELD_BILLING_ADDRESS, CheckoutAddressForm::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addItemShippingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options[static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES]) {
            return $this;
        }

        $fieldOptions = [
            'label' => false,
            'property_path' => static::PROPERTY_PATH_MULTI_SHIPPING_ADDRESSES,
            'entry_type' => CheckoutMultiShippingAddressesForm::class,
            'entry_options' => [
                'data_class' => ItemTransfer::class,
                'label' => false,
                CheckoutMultiShippingAddressesForm::OPTION_VALIDATION_GROUP => static::GROUP_SHIPPING_ADDRESS,
                CheckoutMultiShippingAddressesForm::OPTION_ADDRESS_CHOICES => $this->getMultiShippingAddressChoices($options),
                CheckoutMultiShippingAddressesForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
                CheckoutMultiShippingAddressesForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
            ],
        ];

        $builder->add(static::FIELD_MULTI_SHIPPING_ADDRESSES, CollectionType::class, $fieldOptions);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addItemShippingAddressForBundlesSubForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options[static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES]) {
            return $this;
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $fieldOptions = [
                'label' => false,
                'property_path' => static::PROPERTY_PATH_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS,
                'data' => new \ArrayObject($options[static::OPTION_BUNDLE_ITEMS]),
                'mapped' => false,
                'entry_type' => CheckoutMultiShippingAddressesForm::class,
                'entry_options' => [
                    'data_class' => ItemTransfer::class,
                    'label' => false,
                    CheckoutMultiShippingAddressesForm::OPTION_VALIDATION_GROUP => static::GROUP_SHIPPING_ADDRESS,
                    CheckoutMultiShippingAddressesForm::OPTION_ADDRESS_CHOICES => $this->getMultiShippingAddressChoices($options),
                    CheckoutMultiShippingAddressesForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
                    CheckoutMultiShippingAddressesForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
                ],
            ];

            $event->getForm()->add(static::FIELD_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS, CollectionType::class, $fieldOptions);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIsMultipleShipmentEnabledField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_MULTIPLE_SHIPMENT_ENABLED, HiddenType::class, [
            'mapped' => false,
            'data' => $options[static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES],
        ]);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return string[]
     */
    protected function getShippingAddressChoices(array $options): array
    {
        if (!$options[static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES]) {
            return $options[static::OPTION_ADDRESS_CHOICES];
        }

        $addressChoices = $options[static::OPTION_ADDRESS_CHOICES];
        $addressChoices[static::GLOSSARY_KEY_DELIVER_TO_MULTIPLE_ADDRESSES] = CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;

        return $addressChoices;
    }

    /**
     * @param array $options
     *
     * @return string[]
     */
    protected function getMultiShippingAddressChoices(array $options): array
    {
        return $options[static::OPTION_ADDRESS_CHOICES];
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\IsFalse
     */
    protected function createBillingSameAsShippingConstraint(): IsFalse
    {
        return new IsFalse([
            'message' => static::VALIDATION_BILLING_SAME_AS_SHIPPING_MESSAGE,
            'groups' => static::GROUP_BILLING_SAME_AS_SHIPPING,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCustomerAddressFieldNotEmpty(FormInterface $form): bool
    {
        return !$form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            || $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() !== CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCompanyUnitAddressFieldNotEmpty(FormInterface $form): bool
    {
        return !$form->has(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)
            || $form->get(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)->getData();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCustomerAddressExistAndNotEmpty(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData() !== CheckoutAddressForm::VALUE_ADD_NEW_ADDRESS;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCompanyUnitAddressFieldExistAndNotEmpty(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)->getData();
    }
}
