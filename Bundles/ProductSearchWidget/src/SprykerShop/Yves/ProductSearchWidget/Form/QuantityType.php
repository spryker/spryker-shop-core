<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-04-22
 * Time: 08:24
 */

namespace SprykerShop\Yves\ProductSearchWidget\Form;


use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class QuantityType extends NumberType
{
    /**
     * @param FormView $view
     * @param FormInterface $form
     *
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['type'] = 'number';
    }
}
