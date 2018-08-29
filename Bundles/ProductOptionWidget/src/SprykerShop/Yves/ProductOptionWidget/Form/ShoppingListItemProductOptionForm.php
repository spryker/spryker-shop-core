<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerShop\Yves\ProductOptionWidget\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ShoppingListItemProductOptionForm extends AbstractType
{
    protected const FIELD_SHOPPING_LIST_PRODUCT_OPTIONS = 'product_options';
    protected const TEMPLATE_PATH = '@ProductOptionWidget/views/shopping-list-option-configurator/shopping-list-option-configurator.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductOptionsSelectors($builder);
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
     *
     * @return void
     */
    protected function addProductOptionsSelectors(FormBuilderInterface $builder): void
    {
        $builder->add(
            static::FIELD_SHOPPING_LIST_PRODUCT_OPTIONS,
            TextareaType::class // todo: update to selectors
        );
    }
}