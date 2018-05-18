<?php

namespace SprykerShop\Yves\CompanyUserPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

class CompanyUserController extends AbstractController
{
    public function changeAction()
    {
        $data = [];
        return $this->view($data, [], '@CompanyUserPage/views/company-user/change.twig');
    }
}
