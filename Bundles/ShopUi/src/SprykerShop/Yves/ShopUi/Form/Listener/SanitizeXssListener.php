<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Form\Listener;

use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface;
use SprykerShop\Yves\ShopUi\Form\Type\Extension\SanitizeXssTypeExtension;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SanitizeXssListener implements EventSubscriberInterface
{
    /**
     * @var int
     */
    protected const LISTENER_PRIORITY = 1000;

    /**
     * @var \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface
     */
    protected ShopUiToUtilSanitizeXssServiceInterface $utilSanitizeXssService;

    /**
     * @param \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface $utilSanitizeXssService
     */
    public function __construct(ShopUiToUtilSanitizeXssServiceInterface $utilSanitizeXssService)
    {
        $this->utilSanitizeXssService = $utilSanitizeXssService;
    }

    /**
     * @return array<string, list<string|int>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => ['sanitizeSubmittedData', static::LISTENER_PRIORITY],
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function sanitizeSubmittedData(FormEvent $event): void
    {
        $data = $event->getData();
        if (!is_string($data)) {
            return;
        }

        $formConfig = $event->getForm()->getConfig();
        $data = $this->utilSanitizeXssService->sanitizeXss(
            $data,
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_ATTRIBUTES, []),
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_HTML_TAGS, []),
        );

        $event->setData($this->utilSanitizeXssService->sanitizeXss(
            $data,
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_ATTRIBUTES, []),
            $formConfig->getOption(SanitizeXssTypeExtension::OPTION_ALLOWED_HTML_TAGS, []),
        ));
    }
}
