<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfiguratorStateForm extends AbstractType
{
    protected const FILED_SLOTS = 'slots';
    protected const METHOD_GET = 'GET';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(static::METHOD_GET);
        $this->addSlotsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSlotsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SLOTS, CollectionType::class, [
            'entry_type' => SlotStateForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
        ]);

        return $this;
    }
}
