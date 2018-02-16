<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 2/15/18
 * Time: 17:29
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemEmbeddedForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchField', ChoiceType::class, [
                'choices' => $this->getSearchFieldChoices(),
            ])
            ->add('searchQuery', TextType::class, [
                'required' => false,
            ])
            ->add('sku', HiddenType::class, [
                'required' => false,
            ])
            ->add('qty', IntegerType::class, [
                'required' => false,
            ])
            //->add('unit', ChoiceType::class)
            ->add('price', TextType::class, [
                'disabled' => true,
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderItemTransfer::class,
        ]);
    }

    protected function getSearchFieldChoices()
    {
        return [
            'SKU / Name' => 'name'
        ];
    }
}
