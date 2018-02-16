<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 2/16/18
 * Time: 10:10
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuickOrderForm extends AbstractType
{
    public const SUBMIT_BUTTON_ADD_TO_CART = 'addToCart';

    public const SUBMIT_BUTTON_CREATE_ORDER = 'createOrder';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('items', CollectionType::class, [
                'entry_type' => OrderItemEmbeddedForm::class,
                'allow_add' => true,
                //'allow_delete' => true,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrder::class,
        ]);
    }

}
