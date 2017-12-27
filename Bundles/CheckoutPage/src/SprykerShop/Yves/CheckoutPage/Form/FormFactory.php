<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CheckoutPage\Form;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Form\FormCollectionHandler;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToUtilValidateServiceInterface;
use SprykerShop\Yves\CheckoutPage\Form\DataProvider\SubFormDataProviders;
use SprykerShop\Yves\CheckoutPage\Form\Steps\PaymentForm;
use SprykerShop\Yves\CheckoutPage\Form\Steps\ShipmentForm;
use SprykerShop\Yves\CheckoutPage\Form\Steps\SummaryForm;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function createPaymentMethodSubForms()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PAYMENT_SUB_FORMS);
    }
    
    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createCustomerFormCollection()
    {
        return $this->createFormCollection($this->getCustomerFormTypes());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createAddressFormCollection()
    {
        return $this->createFormCollection($this->getAddressFormTypes(), $this->getAddressFormDataProvider());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createShipmentFormCollection()
    {
        return $this->createFormCollection($this->getShipmentFormTypes(), $this->getShipmentFormDataProviderPlugin());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface[]
     */
    protected function getShipmentFormTypes()
    {
        return [
            $this->createShipmentForm(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\Steps\ShipmentForm
     */
    protected function createShipmentForm()
    {
        return new ShipmentForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected function getShipmentFormDataProviderPlugin()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SHIPMENT_FORM_DATA_PROVIDER);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createPaymentFormCollection()
    {
        $createPaymentSubForms = $this->createPaymentMethodSubForms();
        $paymentFormType = $this->createPaymentForm($createPaymentSubForms);
        $subFormDataProvider = $this->createSubFormDataProvider($createPaymentSubForms);

        return $this->createSubFormCollection($paymentFormType, $subFormDataProvider);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subForms
     *
     * @return \SprykerShop\Yves\CheckoutPage\Form\DataProvider\SubFormDataProviders
     */
    protected function createSubFormDataProvider(SubFormPluginCollection $subForms)
    {
        return new SubFormDataProviders($subForms);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createSummaryFormCollection()
    {
        return $this->createFormCollection($this->createSummaryFormTypes());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface[]
     */
    protected function getCustomerFormTypes()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CUSTOMER_STEP_SUB_FORMS);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface[]
     */
    protected function getAddressFormTypes()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::ADDRESS_STEP_SUB_FORMS);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface[]
     */
    protected function createSummaryFormTypes()
    {
        return [
            $this->createSummaryForm(),
        ];
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subForms
     *
     * @return \SprykerShop\Yves\CheckoutPage\Form\Steps\PaymentForm
     */
    protected function createPaymentForm(SubFormPluginCollection $subForms)
    {
        return new PaymentForm($subForms);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\Steps\SummaryForm
     */
    protected function createSummaryForm()
    {
        return new SummaryForm();
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface[] $formTypes
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface|null $dataProvider
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    protected function createFormCollection(array $formTypes, StepEngineFormDataProviderInterface $dataProvider = null)
    {
        return new FormCollectionHandler($formTypes, $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY), $dataProvider);
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $formType
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface $dataProvider
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    protected function createSubFormCollection($formType, StepEngineFormDataProviderInterface $dataProvider)
    {
        return new FormCollectionHandler([$formType], $this->getFormFactory(), $dataProvider);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::STORE);
    }

    /**
     * @return CheckoutPageToCustomerClientInterface
     */
    protected function getCustomerClient(): CheckoutPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToUtilValidateServiceInterface
     */
    protected function getUtilValidateService(): CheckoutPageToUtilValidateServiceInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_UTIL_VALIDATE);
    }

    /**
     * @return StepEngineFormDataProviderInterface|null
     */
    protected function getAddressFormDataProvider()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::ADDRESS_STEP_FORM_DATA_PROVIDER);
    }
}
