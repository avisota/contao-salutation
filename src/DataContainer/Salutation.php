<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2017 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2017
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
                array('getBreadCrumb', 1)
            )
        );
    }

    /**
     * Get the bread crumb elements.
     *
     * @param GetBreadcrumbEvent $event This event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getBreadCrumb(GetBreadcrumbEvent $event)
    {
        $environment    = $event->getEnvironment();
        $dataDefinition = $environment->getDataDefinition();
        $inputProvider  = $environment->getInputProvider();

        if ('orm_avisota_salutation' !== $dataDefinition->getName()) {
            return;
        }

        if (empty($inputProvider->getParameter('act'))) {
            $this->getBreadCrumbByClearClipboard($event);

            return;
        }

        if ('edit' !== $inputProvider->getParameter('act')) {
            $inputProvider
                ->setParameter('id', ModelId::fromValues('orm_avisota_salutation', 0)->getSerialized());
        }

        $elements = $event->getElements();

        $modelId = ModelId::fromSerialized($inputProvider->getParameter('id'));

        $dataProvider = $environment->getDataProvider($modelId->getDataProviderName());
        $repository   = $dataProvider->getEntityRepository();

        $parentDataDefinition = $environment->getParentDataDefinition();
        if (null === $parentDataDefinition) {
            $event->setElements($elements);

            return;
        }

        $salutationEntity = $repository->findOneBy(array('id' => $modelId->getId()));
        if (null === $salutationEntity) {
            $parentDataProvider = $environment->getDataProvider($parentDataDefinition->getName());
            $parentRepository   = $parentDataProvider->getEntityRepository();

            $parentModelId         = ModelId::fromSerialized($inputProvider->getParameter('pid'));
            $salutationGroupEntity = $parentRepository->findOneBy(array('id' => $parentModelId->getId()));
        }

        if ('edit' === $inputProvider->getParameter('act')) {
            $salutationGroupEntity = $salutationEntity->getSalutationGroup();
        }

        $parentUrlBuilder = new UrlBuilder();
        $parentUrlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('table', $dataDefinition->getName())
            ->setQueryParameter('pid', $inputProvider->getParameter('pid'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/salutation/images/salutation.png',
            'text' => $salutationGroupEntity->getTitle(),
            'url'  => $parentUrlBuilder->getUrl()
        );

        if (null ===  $salutationEntity) {
            $event->setElements($elements);

            return;
        }

        $entityUrlBuilder = new UrlBuilder();
        $entityUrlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('table', $dataDefinition->getName())
            ->setQueryParameter('act', $inputProvider->getParameter('act'))
            ->setQueryParameter('id', $inputProvider->getParameter('id'))
            ->setQueryParameter('pid', $inputProvider->getParameter('pid'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/salutation/images/salutation.png',
            'text' => $salutationEntity->getSalutation(),
            'url'  => $entityUrlBuilder->getUrl()
        );

        $event->setElements($elements);
    }

    /**
     * Get bread crumb after clear the clipboard.
     *
     * @param GetBreadcrumbEvent $event The event.
     *
     * @return void
     */
    private function getBreadCrumbByClearClipboard(GetBreadcrumbEvent $event)
    {
        $environment   = $event->getEnvironment();
        $inputProvider = $environment->getInputProvider();

        if ((false === $inputProvider->hasParameter('clipboard-item'))
            || ('' !== $inputProvider->getParameter('clipboard-item'))
        ) {
            return;
        }

        $elements = $event->getElements();

        $modelId = ModelId::fromSerialized($inputProvider->getParameter('pid'));

        $dataDefinition = $environment->getDataDefinition();
        $dataProvider   = $environment->getDataProvider($modelId->getDataProviderName());
        $repository     = $dataProvider->getEntityRepository();

        $salutationGroupEntity = $repository->findOneBy(array('id' => $modelId->getId()));

        $parentUrlBuilder = new UrlBuilder();
        $parentUrlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('table', $dataDefinition->getName())
            ->setQueryParameter('pid', $inputProvider->getParameter('pid'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/salutation/images/salutation.png',
            'text' => $salutationGroupEntity->getTitle(),
            'url'  => $parentUrlBuilder->getUrl()
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
