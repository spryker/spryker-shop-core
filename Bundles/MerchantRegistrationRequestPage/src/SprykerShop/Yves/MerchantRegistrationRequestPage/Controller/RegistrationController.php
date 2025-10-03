<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Form\MerchantRegistrationRequestPageForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantRegistrationRequestPage\MerchantRegistrationRequestPageFactory getFactory()
 */
class RegistrationController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin::ROUTE_NAME_HOME
     *
     * @var string
     */
    protected const ROUTE_NAME_HOME = 'home';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_REGISTRATION_REQUEST_SUCCESS = 'merchant_registration_request_page.success';

    public function indexAction(Request $request): View|RedirectResponse
    {
        $merchantRegistrationRequestForm = $this->getFactory()
            ->getMerchantRegistrationRequestForm()
            ->handleRequest($request);

        if ($merchantRegistrationRequestForm->isSubmitted() && $merchantRegistrationRequestForm->isValid()) {
            /** @var \Generated\Shared\Transfer\MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer */
            $merchantRegistrationRequestTransfer = $merchantRegistrationRequestForm->getData();
            $merchantRegistrationRequestTransfer->setStore(
                $this->getFactory()->getStoreClient()->getCurrentStore(),
            );
            $merchantRegistrationResponseTransfer = $this->getFactory()
                ->getMerchantRegistrationRequestClient()
                ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

            if ($merchantRegistrationResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_MERCHANT_REGISTRATION_REQUEST_SUCCESS);

                return $this->redirectResponseInternal(static::ROUTE_NAME_HOME);
            }

            foreach ($merchantRegistrationResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }
        }

        return $this->view(
            [
                'merchantRegistrationRequestForm' => $merchantRegistrationRequestForm->createView(),
                'companyFields' => [
                    MerchantRegistrationRequestPageForm::FIELD_COMPANY_NAME,
                    MerchantRegistrationRequestPageForm::FIELD_REGISTRATION_NUMBER,
                    MerchantRegistrationRequestPageForm::FIELD_ADDRESS_1,
                    MerchantRegistrationRequestPageForm::FIELD_ADDRESS_2,
                    MerchantRegistrationRequestPageForm::FIELD_ZIP_CODE,
                    MerchantRegistrationRequestPageForm::FIELD_CITY,
                    MerchantRegistrationRequestPageForm::FIELD_ISO_2_CODE,
                ],
                'userAccountFields' => [
                    MerchantRegistrationRequestPageForm::FIELD_CONTACT_PERSON_TITLE,
                    MerchantRegistrationRequestPageForm::FIELD_CONTACT_PERSON_FIRST_NAME,
                    MerchantRegistrationRequestPageForm::FIELD_CONTACT_PERSON_LAST_NAME,
                    MerchantRegistrationRequestPageForm::FIELD_EMAIL,
                    MerchantRegistrationRequestPageForm::FIELD_CONTACT_PERSON_PHONE,
                    MerchantRegistrationRequestPageForm::FIELD_CONTACT_PERSON_ROLE,
                ],
            ],
            [],
            '@MerchantRegistrationRequestPage/views/registration/index.twig',
        );
    }
}
