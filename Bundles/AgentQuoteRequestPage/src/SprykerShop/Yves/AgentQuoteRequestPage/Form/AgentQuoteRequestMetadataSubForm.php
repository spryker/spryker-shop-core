<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig getConfig()
 */
class AgentQuoteRequestMetadataSubForm extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMetadataFields($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addMetadataFields(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->getFactory()->getAgentQuoteRequestFormMetadataFieldPlugins() as $quoteRequestFormMetadataFieldPlugin) {
            $builder = $quoteRequestFormMetadataFieldPlugin->buildForm($builder, $options);
        }
    }
}
