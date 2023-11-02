<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use ArrayObject;
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
    /**
     * @var string
     */
    public const FIELD_SHIPPING_ADDRESS = 'shippingAddress';

    /**
     * @var string
     */
    public const FIELD_BILLING_ADDRESS = 'billingAddress';

    /**
     * @var string
     */
    public const FIELD_BILLING_SAME_AS_SHIPPING = 'billingSameAsShipping';

    /**
     * @var string
     */
    public const FIELD_MULTI_SHIPPING_ADDRESSES = 'multiShippingAddresses';

    /**
     * @var string
     */
    public const FIELD_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS = 'multiShippingAddressesForBundleItems';

    /**
     * @var string
     */
    public const FIELD_IS_MULTIPLE_SHIPMENT_ENABLED = 'isMultipleShipmentEnabled';

    /**
     * @var string
     */
    public const OPTION_SINGLE_SHIPPING_ADDRESS_CHOICES = 'single_shipping_address_choices';

    /**
     * @var string
     */
    public const OPTION_MULTIPLE_SHIPPING_ADDRESS_CHOICES = 'multiple_shipping_addresses_choices';

    /**
     * @var string
     */
    public const OPTION_BILLING_ADDRESS_CHOICES = 'billing_addresses_choices';

    /**
     * @var string
     */
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    /**
     * @var string
     */
    public const OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES = 'can_deliver_to_multiple_shipping_addresses';

    /**
     * @var string
     */
    public const OPTION_IS_CUSTOMER_LOGGED_IN = 'is_customer_logged_in';

    /**
     * @var string
     */
    public const OPTION_BUNDLE_ITEMS = 'bundleItems';

    public const GROUP_SHIPPING_ADDRESS = self::FIELD_SHIPPING_ADDRESS;

    public const GROUP_BILLING_ADDRESS = self::FIELD_BILLING_ADDRESS;

    public const GROUP_BILLING_SAME_AS_SHIPPING = self::FIELD_BILLING_SAME_AS_SHIPPING;

    /**
     * @var string
     */
    public const VALIDATION_BILLING_SAME_AS_SHIPPING_MESSAGE = 'Billing address should not be specified when shipping to multiple addresses.';

    /**
     * @var string
     */
    protected const PROPERTY_PATH_MULTI_SHIPPING_ADDRESSES = 'items';

    /**
     * @var string
     */
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP, static::GROUP_SHIPPING_ADDRESS];

                if (!$form->get(static::FIELD_BILLING_SAME_AS_SHIPPING)->getData()) {
                    $validationGroups[] = static::GROUP_BILLING_ADDRESS;
                }

                return $validationGroups;
            },
            static::OPTION_SINGLE_SHIPPING_ADDRESS_CHOICES => [],
            static::OPTION_MULTIPLE_SHIPPING_ADDRESS_CHOICES => [],
            static::OPTION_BILLING_ADDRESS_CHOICES => [],
        ]);

        $resolver->setDefined(static::OPTION_SINGLE_SHIPPING_ADDRESS_CHOICES)
            ->setDefined(static::OPTION_MULTIPLE_SHIPPING_ADDRESS_CHOICES)
            ->setDefined(static::OPTION_BILLING_ADDRESS_CHOICES)
            ->setRequired(static::OPTION_COUNTRY_CHOICES)
            ->setRequired(static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES)
            ->setRequired(static::OPTION_IS_CUSTOMER_LOGGED_IN)
            ->setRequired(static::OPTION_BUNDLE_ITEMS)
            ->setRequired(CheckoutMultiShippingAddressesForm::OPTION_MULTI_SHIPPING_OPTIONS);

        $this->configureOptionsByCheckoutAddressCollectionFormExpanderPlugins($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addShippingAddressSubForm($builder, $options)
            ->addItemShippingAddressSubForm($builder, $options)
            ->addItemShippingAddressForBundlesSubForm($builder, $options)
            ->addSameAsShippingCheckboxField($builder)
            ->addBillingAddressSubForm($builder, $options)
            ->addIsMultipleShipmentEnabledField($builder, $options)
            ->executeCheckoutAddressCollectionFormExpanderPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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
                $skipValidation = $form->getExtraData()[AddressForm::EXTRA_FIELD_SKIP_VALIDATION] ?? null;
                if ($skipValidation || $this->isIdCustomerAddressFieldNotEmpty($form) || $this->isIdCompanyUnitAddressFieldNotEmpty($form)) {
                    return false;
                }

                return [static::GROUP_SHIPPING_ADDRESS];
            },
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => static::GROUP_SHIPPING_ADDRESS,
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $options[static::OPTION_SINGLE_SHIPPING_ADDRESS_CHOICES],
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
            CheckoutAddressForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
        ];

        $builder->add(static::FIELD_SHIPPING_ADDRESS, CheckoutAddressForm::class, $fieldOptions);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($shipmentService) {
            $this->hydrateShippingAddressSubFormDataFromItemLevelShippingAddresses($event, $shipmentService);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            return $this->mapSubmittedShippingAddressSubFormDataToItemLevelShippingAddresses($event, $options);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    protected function copyBundleItemLevelShippingAddressesToItemLevelShippingAddresses(FormEvent $event, array $options): FormEvent
    {
        $quoteTransfer = $event->getData();

        if (!$quoteTransfer instanceof QuoteTransfer) {
            return $event;
        }

        $quoteTransfer = $this->getFactory()
            ->createShipmentExpander()
            ->expandShipmentForBundleItems($quoteTransfer, $options[static::OPTION_BUNDLE_ITEMS]);

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

        $quoteTransfer = $this->executeCheckoutAddressStepPreGroupItemsByShipmentPlugins($quoteTransfer);

        $shipmentGroupCollection = $this->mergeShipmentGroupsByShipmentHash(
            $shipmentService->groupItemsByShipment($quoteTransfer->getItems()),
            $shipmentService->groupItemsByShipment($quoteTransfer->getBundleItems()),
        );

        $shippingAddressForm = $event->getForm()->get(static::FIELD_SHIPPING_ADDRESS);

        if (count($shipmentGroupCollection) > 1) {
            $this->setDeliverToMultipleAddressesEnabled($shippingAddressForm);

            return;
        }

        if ($this->isDeliverToMultipleAddressesEnabled($shippingAddressForm) || $shipmentGroupCollection->count() < 1) {
            return;
        }

        $shipmentGroupTransfer = $shipmentGroupCollection->getIterator()->current();

        if (!$shipmentGroupTransfer->getShipment() || !$shipmentGroupTransfer->getShipment()->getShippingAddress()) {
            return;
        }

        $shippingAddressForm->setData(clone $shipmentGroupTransfer->getShipment()->getShippingAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeCheckoutAddressStepPreGroupItemsByShipmentPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $checkoutAddressStepPreGroupItemsByShipmentPlugins = $this->getFactory()->getCheckoutAddressStepPreGroupItemsByShipmentPlugins();
        foreach ($checkoutAddressStepPreGroupItemsByShipmentPlugins as $checkoutAddressStepPreGroupItemsByShipmentPlugin) {
            $quoteTransfer = $checkoutAddressStepPreGroupItemsByShipmentPlugin->preGroupItemsByShipment($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupCollection
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentGroupTransfer> $bundleItemsShipmentGroupCollection
     *
     * @return \ArrayObject<string, \Generated\Shared\Transfer\ShipmentGroupTransfer>
     */
    protected function mergeShipmentGroupsByShipmentHash(
        ArrayObject $shipmentGroupCollection,
        ArrayObject $bundleItemsShipmentGroupCollection
    ): ArrayObject {
        $indexedShipmentGroups = [];

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $indexedShipmentGroups[$shipmentGroupTransfer->getHash()] = $shipmentGroupTransfer;
        }

        foreach ($bundleItemsShipmentGroupCollection as $shipmentGroupTransfer) {
            $indexedShipmentGroups[$shipmentGroupTransfer->getHash()] = $shipmentGroupTransfer;
        }

        return new ArrayObject($indexedShipmentGroups);
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    protected function mapSubmittedShippingAddressSubFormDataToItemLevelShippingAddresses(FormEvent $event, array $options): FormEvent
    {
        $quoteTransfer = $event->getData();
        if (!($quoteTransfer instanceof QuoteTransfer)) {
            return $event;
        }

        $form = $event->getForm();
        $shippingAddressFrom = $form->get(static::FIELD_SHIPPING_ADDRESS);

        if ($this->isDeliverToMultipleAddressesEnabled($shippingAddressFrom)) {
            return $this->copyBundleItemLevelShippingAddressesToItemLevelShippingAddresses($event, $options);
        }

        $shippingAddressTransfer = $shippingAddressFrom->getData();
        $shipmentTransfer = $this->getQuoteItemShipmentTransfer($quoteTransfer);
        $shipmentTransfer->setShippingAddress($shippingAddressTransfer);

        $quoteTransfer = $this->mapShipmentToItemLevelShipments($quoteTransfer, $shipmentTransfer);
        $quoteTransfer = $this->mapShipmentToBundleItemLevelShipments($quoteTransfer, $shipmentTransfer);

        $event->setData($quoteTransfer);

        return $event;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapShipmentToItemLevelShipments(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mapShipmentToBundleItemLevelShipments(
        QuoteTransfer $quoteTransfer,
        ShipmentTransfer $shipmentTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getQuoteItemShipmentTransfer(QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer */
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
            ->setIdCustomerAddress((int)CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES);

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
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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

                if (
                    $this->isIdCustomerAddressFieldNotEmpty($form)
                    || $this->isIdCompanyUnitAddressFieldNotEmpty($form)
                ) {
                    return false;
                }

                return [static::GROUP_BILLING_ADDRESS];
            },
            'required' => true,
            CheckoutAddressForm::OPTION_VALIDATION_GROUP => static::GROUP_BILLING_ADDRESS,
            CheckoutAddressForm::OPTION_ADDRESS_CHOICES => $options[static::OPTION_BILLING_ADDRESS_CHOICES],
            CheckoutAddressForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
            CheckoutAddressForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
        ];

        $builder->add(static::FIELD_BILLING_ADDRESS, CheckoutAddressForm::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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
                CheckoutMultiShippingAddressesForm::OPTION_ADDRESS_CHOICES => $options[static::OPTION_MULTIPLE_SHIPPING_ADDRESS_CHOICES],
                CheckoutMultiShippingAddressesForm::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
                CheckoutMultiShippingAddressesForm::OPTION_IS_CUSTOMER_LOGGED_IN => $options[static::OPTION_IS_CUSTOMER_LOGGED_IN],
                ...$options[CheckoutMultiShippingAddressesForm::OPTION_MULTI_SHIPPING_OPTIONS],
            ],
        ];

        $builder->add(static::FIELD_MULTI_SHIPPING_ADDRESSES, CollectionType::class, $fieldOptions);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addItemShippingAddressForBundlesSubForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options[static::OPTION_CAN_DELIVER_TO_MULTIPLE_SHIPPING_ADDRESSES] || !count($options[static::OPTION_BUNDLE_ITEMS])) {
            return $this;
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $fieldOptions = [
                'label' => false,
                'property_path' => static::PROPERTY_PATH_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS,
                'data' => new ArrayObject($options[static::OPTION_BUNDLE_ITEMS]),
                'mapped' => false,
                'entry_type' => CheckoutMultiShippingAddressesForm::class,
                'entry_options' => [
                    'data_class' => ItemTransfer::class,
                    'label' => false,
                    ...$options[CheckoutMultiShippingAddressesForm::OPTION_MULTI_SHIPPING_OPTIONS],
                ],
            ];

            $event->getForm()->add(static::FIELD_MULTI_SHIPPING_ADDRESSES_FOR_BUNDLE_ITEMS, CollectionType::class, $fieldOptions);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
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
        return $form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData();
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isIdCompanyUnitAddressFieldNotEmpty(FormInterface $form): bool
    {
        return $form->has(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)
            && $form->get(CheckoutAddressForm::FIELD_ID_COMPANY_UNIT_ADDRESS)->getData();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function executeCheckoutAddressCollectionFormExpanderPlugins(FormBuilderInterface $formBuilder, array $options)
    {
        foreach ($this->getFactory()->getCheckoutAddressCollectionFormExpanderPlugins() as $checkoutAddressCollectionFormExpanderPlugin) {
            $formBuilder = $checkoutAddressCollectionFormExpanderPlugin->expand($formBuilder, $options);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    protected function configureOptionsByCheckoutAddressCollectionFormExpanderPlugins(OptionsResolver $resolver): void
    {
        foreach ($this->getFactory()->getCheckoutAddressCollectionFormExpanderPlugins() as $checkoutAddressCollectionFormExpanderPlugin) {
            $checkoutAddressCollectionFormExpanderPlugin->configureOptions($resolver);
        }
    }
}
