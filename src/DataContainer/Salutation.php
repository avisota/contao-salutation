<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Salutation\DataContainer;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Salutation
 *
 * @package Avisota\Contao\Salutation\DataContainer
 */
class Salutation implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            GetBreadcrumbEvent::NAME => array(
                array('getBreadCrumb')
            )
        );
    }

    /**
     * Get the bread crumb elements.
     *
     * @param GetBreadcrumbEvent $event This event.
     *
     * @return void
     */
    public function getBreadCrumb(GetBreadcrumbEvent $event)
    {
        $environment   = $event->getEnvironment();
        $inputProvider = $environment->getInputProvider();

        if (!$inputProvider->hasParameter('id')) {
            return;
        }

        $salutationModelId = ModelId::fromSerialized($inputProvider->getParameter('id'));
        if ($salutationModelId->getDataProviderName() !== 'orm_avisota_salutation') {
            return;
        }

        $elements = $event->getElements();

        $urlSalutationBuilder = new UrlBuilder();
        $urlSalutationBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/salutation/images/salutation.png',
            'text' => $GLOBALS['TL_LANG']['MOD']['avisota_salutation'][0],
            'url'  => $urlSalutationBuilder->getUrl()
        );

        $urlSalutationGroupBuilder = new UrlBuilder();
        $urlSalutationGroupBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('table', $inputProvider->getParameter('table'))
            ->setQueryParameter('pid', $inputProvider->getParameter('pid'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $salutationGroupModelId = ModelId::fromSerialized($inputProvider->getParameter('pid'));
        $dataProvider           = $environment->getDataProvider($salutationGroupModelId->getDataProviderName());
        $model                  =
            $dataProvider->fetch($dataProvider->getEmptyConfig()->setId($salutationGroupModelId->getId()));
        $entity                 = $model->getEntity();

        $elements[] = array(
            'icon' => 'assets/avisota/subscription-recipient/images/recipients.png',
            'text' => $entity->getTitle(),
            'url'  => $urlSalutationGroupBuilder->getUrl()
        );

        $event->setElements($elements);
    }

    /**
     * Add the type of content element
     *
     * @param array
     *
     * @return string
     */
    public function addElement($contentData)
    {
        return sprintf(
            '<div>%s</div>' . "\n",
            $contentData['salutation']
        );
    }
}
