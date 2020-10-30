<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Configurator\DateTimeConfiguratorPageExample;

use SprykerSdk\ProductConfigurationSdk\Checksum\CrcProductConfigurationDataChecksumGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ConfiguratorPage
{
    protected const REQUEST_PARAMETER_TOKEN = 'token';
    protected const REQUEST_PARAMETER_GET_CONFIGURATION_BY_TOKEN = 'getConfigurationByToken';
    protected const REQUEST_PARAMETER_PREPARER_CONFIGURATION = 'prepareConfiguration';

    protected const CONFIGURATOR_SESSION_KEY = 'CONFIGURATOR_SESSION_KEY';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function __construct()
    {
        $this->session = new Session();
        $this->request = Request::createFromGlobals();
    }

    /**
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function render()
    {
        if ($this->request->isMethod(Request::METHOD_GET) && $this->request->query->has(static::REQUEST_PARAMETER_GET_CONFIGURATION_BY_TOKEN)) {
            return $this->getRequestDataByTokenAction();
        }

        if ($this->request->isMethod(Request::METHOD_POST) && $this->request->query->has(static::REQUEST_PARAMETER_PREPARER_CONFIGURATION)) {
            return $this->prepareConfigurationResponseAction();
        }

        if ($this->request->isMethod(Request::METHOD_POST)) {
            return $this->createTokenAction();
        }

        return $this->renderHtmlPageAction();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createTokenAction(): Response
    {
        if (!$this->session->start()) {
            return new JsonResponse([
                'isSuccessful' => false,
                'message' => 'Can\'t start session.',
            ]);
        }

        $this->session->set(
            static::CONFIGURATOR_SESSION_KEY,
            json_decode($this->request->getContent(), true) ?? []
        );

        return new JsonResponse([
            'isSuccessful' => true,
            'configuratorRedirectUrl' => $this->createConfiguratorRedirectUrl(),
        ], Response::HTTP_OK);
    }

    /**
     * @return string
     */
    protected function renderHtmlPageAction(): string
    {
        return file_get_contents(
            __DIR__ . DIRECTORY_SEPARATOR . 'Theme' . DIRECTORY_SEPARATOR . 'index.html'
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getRequestDataByTokenAction(): Response
    {
        return new JsonResponse(
            ['data' => $this->getDataFromSession()],
            Response::HTTP_OK
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareConfigurationResponseAction(): Response
    {
        $productConfiguration = $this->request->request->all() ?? [];
        $checkSum = (new CrcProductConfigurationDataChecksumGenerator(
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_HEX_INITIALIZATION_VECTOR') ?: ''
        ))->generateProductConfigurationDataChecksum(
            $productConfiguration,
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_ENCRYPTION_KEY') ?: ''
        );

        return new JsonResponse(
            array_merge($productConfiguration, ['checkSum' => $checkSum, 'timestamp' => time()]),
            Response::HTTP_OK
        );
    }

    /**
     * @return array
     */
    protected function getDataFromSession(): array
    {
        $this->session->setId($this->request->get(static::REQUEST_PARAMETER_GET_CONFIGURATION_BY_TOKEN));
        $this->session->start();

        return $this->session->get(static::CONFIGURATOR_SESSION_KEY, []);
    }

    /**
     * @return string
     */
    protected function createConfiguratorRedirectUrl(): string
    {
        return sprintf(
            '%s://%s?token=%s',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_PORT') === '443' ? 'https' : 'http',
            getenv('SPRYKER_PRODUCT_CONFIGURATOR_HOST') ?: '',
            htmlspecialchars($this->session->getId())
        );
    }
}
