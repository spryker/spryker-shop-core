<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerShop\Yves\ProductOptionWidget\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionForm extends AbstractType
{
    public const PRODUCT_OPTION_GROUP_KEY = 'product_option_group';
    protected const TEMPLATE_PATH = '@ProductOptionWidget/views/shopping-list-option-form/shopping-list-option-form.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductOptionsSelectors($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        $view->vars['template_path'] = $this->getTemplatePath();
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addProductOptionsSelectors(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $form = $event->getForm();
            $data = $event->getData();
            /** @var \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer $option */
            foreach ($options[static::PRODUCT_OPTION_GROUP_KEY] as $option) {
                $form->add(str_replace('.', '_', $option->getName()), ChoiceType::class, [
                    'label' => $option->getName(),
                    'choices' => $option->getProductOptionValues()->getArrayCopy(),
                    'multiple' => false,
                    'inherit_data' => true,
                ]);
            }
        });
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::PRODUCT_OPTION_GROUP_KEY);

        $resolver->setDefaults([
            static::PRODUCT_OPTION_GROUP_KEY,
        ]);
    }
}