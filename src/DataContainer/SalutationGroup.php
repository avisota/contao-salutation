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

use Contao\Config;
use Contao\Controller;
use Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider;
use Contao\Session;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetBreadcrumbEvent;
use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use ContaoCommunityAlliance\DcGeneral\DcGeneralEvents;
use ContaoCommunityAlliance\DcGeneral\Event\ActionEvent;
use ContaoCommunityAlliance\UrlBuilder\UrlBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SalutationGroup
 *
 * @package Avisota\Contao\Salutation\DataContainer
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
class SalutationGroup implements EventSubscriberInterface
{

    /**
     * Returns the events to which this class has subscribed.
     *
     * Return format:
     *     array(
     *         array('event' => 'the-event-name', 'method' => 'onEventName', 'class' => 'some-class', 'format' =>
     *         'json'), array(...),
     *     )
     *
     * The class may be omitted if the class wants to subscribe to events of all classes.
     * Same goes for the format key.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            GetBreadcrumbEvent::NAME => array(
                array('getBreadCrumb')
            ),

            DcGeneralEvents::ACTION => array(
                array('generateStandardGroup')
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
        $environment    = $event->getEnvironment();
        $dataDefinition = $environment->getDataDefinition();
        $inputProvider  = $environment->getInputProvider();
        $translator     = $environment->getTranslator();

        $modelParameter = $inputProvider->hasParameter('act') ? 'id' : 'pid';

        if ($dataDefinition->getName() !== 'orm_avisota_salutation_group'
            || !$inputProvider->hasParameter($modelParameter)
        ) {
            return;
        }

        $modelId = ModelId::fromSerialized($inputProvider->getParameter($modelParameter));
        if ($modelId->getDataProviderName() !== 'orm_avisota_salutation_group') {
            return;
        }

        $elements = $event->getElements();

        $urlBuilder = new UrlBuilder();
        $urlBuilder->setPath('contao/main.php')
            ->setQueryParameter('do', $inputProvider->getParameter('do'))
            ->setQueryParameter('ref', TL_REFERER_ID);

        $elements[] = array(
            'icon' => 'assets/avisota/salutation/images/salutation.png',
            'text' => $translator->translate('avisota_salutation.0', 'MOD'),
            'url'  => $urlBuilder->getUrl()
        );

        $event->setElements($elements);
    }

    public function generateStandardGroup(ActionEvent $event)
    {
        $action         = $event->getAction();
        $environment    = $event->getEnvironment();
        $dataDefinition = $environment->getDataDefinition();

        if ($dataDefinition->getName() !== 'orm_avisota_salutation_group'
            || $action->getName() !== 'generate'
        ) {
            return;
        }

        global $AVISOTA_SALUTATION;

        $eventDispatcher = $environment->getEventDispatcher();
        $translator      = $environment->getTranslator();

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('avisota_salutation')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_salutation_group')
        );

        $predefinedSalutations = $AVISOTA_SALUTATION;

        $entityDataProvider = new EntityDataProvider();
        $entityDataProvider->setBaseConfig(array('source' => 'orm_avisota_salutation_group'));
        $entityManager  = $entityDataProvider->getEntityManager();
        $entityAccessor = $entityDataProvider->getEntityAccessor();

        $salutationGroup = new \Avisota\Contao\Entity\SalutationGroup();
        $salutationGroup->setTitle('Default group generated at ' . date(Config::get('datimFormat')));
        $salutationGroup->setAlias(null);

        $sorting = 64;
        foreach ($predefinedSalutations as $index => $predefinedSalutation) {
            $salutation = new \Avisota\Contao\Entity\Salutation();

            $entityAccessor->setProperties($salutation, $predefinedSalutation);

            $salutation->setSalutation($translator->translate((string) $index, 'avisota_salutation'));
            $salutation->setSalutationGroup($salutationGroup);
            $salutation->setSorting($sorting);
            $salutationGroup->addSalutation($salutation);
            $sorting *= 2;
        }

        $entityManager->persist($salutationGroup);
        $entityManager->flush($salutationGroup);

        $sessionConfirm = Session::getInstance()->get('TL_CONFIRM');
        if (!is_array($sessionConfirm)) {
            $sessionConfirm = (array) $sessionConfirm;
        }
        $sessionConfirm[] = $translator->translate('group_generated', 'orm_avisota_salutation_group');
        Session::getInstance()->set('TL_CONFIRM', $sessionConfirm);

        Controller::redirect('contao/main.php?do=avisota_salutation');
    }
}
