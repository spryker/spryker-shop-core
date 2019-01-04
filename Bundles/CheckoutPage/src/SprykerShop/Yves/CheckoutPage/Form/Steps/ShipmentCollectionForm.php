<?php
/**
 * Created by PhpStorm.
 * User: aleksandr.kovalchuk
 * Date: 2019-01-02
 * Time: 15:10
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentCollectionForm extends AbstractType
{
    public const BLOCK_PREFIX = 'shipmentCollectionForm';
    public const FIELD_ID_SHIPMENT_METHODS = 'idShipmentMethods';
    public const OPTION_SHIPMENT_METHODS_BY_GROUP = 'shipmentMethodsByGroup';

    public function getBlockPrefix()
    {
        return static::BLOCK_PREFIX;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIdShipmentMethods($builder, $options);
    }

    protected function addIdShipmentMethods($builder, array $options)
    {
        $builder->add(static::FIELD_ID_SHIPMENT_METHODS, CollectionType::class, [
            'entry_type' => ShipmentForm::class,
            'allow_add' => false,
            'allow_delete' => false,
//            'entry_options' => [
//                'data_class' => $options[static::OPTION_DATA_CLASS_ATTRIBUTES],
//                CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES => $options[CmsPageAttributesFormType::OPTION_AVAILABLE_LOCALES],
//            ],
        ]);

//        $builder->get(static::FIELD_PAGE_ATTRIBUTES)
//            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_SHIPMENT_METHODS_BY_GROUP);
    }

}