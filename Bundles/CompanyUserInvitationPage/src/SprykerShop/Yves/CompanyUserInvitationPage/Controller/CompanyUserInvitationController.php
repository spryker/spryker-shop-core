<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;
use SprykerShop\Yves\CompanyUserInvitationPage\Form\CompanyUserInvitationForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class CompanyUserInvitationController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $companyUserInvitationForm = $this->getFactory()
            ->createCompanyUserInvitationPageFormFactory()
            ->getCompanyUserInvitationForm()
            ->handleRequest($request);

        if ($companyUserInvitationForm->isSubmitted() && $companyUserInvitationForm->isValid()) {
            $data['importResult'] = $this->importInvitations($companyUserInvitationForm);
        }

        $data['form'] = $companyUserInvitationForm->createView();

        return $this->view($data, [], '@CompanyUserInvitationPage/views/invitation/index.twig');
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserInvitationForm
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    protected function importInvitations(FormInterface $companyUserInvitationForm): CompanyUserInvitationImportResultTransfer
    {
        $filePath = $this->getFilePath($companyUserInvitationForm);

        return $this->getFactory()->getCompanyUserInvitationClient()->importInvitations($filePath);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUserInvitationForm
     *
     * @return string
     */
    protected function getFilePath(FormInterface $companyUserInvitationForm): string
    {
        return $companyUserInvitationForm
            ->getData()[CompanyUserInvitationForm::FIELD_INVITATIONS_LIST]
            ->getPathname();
    }
}
