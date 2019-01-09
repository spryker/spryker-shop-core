<?php
/**
 * Created by PhpStorm.
 * User: aleksandr.kovalchuk
 * Date: 2019-01-02
 * Time: 15:10
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupsTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentCollectionForm extends AbstractType
{
    public const BLOCK_PREFIX = 'shipmentCollectionForm';
    public const FIELD_SHIPMENT_COLLECTION_GROUP = 'shipmentGroups';
    public const OPTION_SHIPMENT_METHODS_BY_GROUP = 'shipmentMethodsByGroup';

    public function getBlockPrefix()
    {
        return static::BLOCK_PREFIX;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addShipmentGroups($builder, $options);
    }

    protected function addShipmentGroups(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SHIPMENT_COLLECTION_GROUP, CollectionType::class, [
            'entry_type' => ShipmentGroupForm::class,
            'allow_add' => false,
            'allow_delete' => false,
            'entry_options' => [
                'data_class' => ShipmentGroupTransfer::class,
                static::OPTION_SHIPMENT_METHODS_BY_GROUP => $options[static::OPTION_SHIPMENT_METHODS_BY_GROUP],
            ],
        ]);

        return $this;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuoteTransfer::class
        ]);
        $resolver->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP);
    }

}