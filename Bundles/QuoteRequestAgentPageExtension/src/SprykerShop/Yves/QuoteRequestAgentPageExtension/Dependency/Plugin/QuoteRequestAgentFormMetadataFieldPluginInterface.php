<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormBuilderInterface;

interface QuoteRequestAgentFormMetadataFieldPluginInterface
{
    /**
     * Specification:
     *  - Extends quote request form metadata field with additional input fields.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface;
}
