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

use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\Compatibility\DcCompat;
use ContaoCommunityAlliance\DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class OptionsBuilder
 *
 * @package Avisota\Contao\Salutation\DataContainer
 */
class OptionsBuilder implements EventSubscriberInterface
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
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'avisota.create-salutation-group-options' => array(
                array('createSalutationGroups'),
            ),
        );
    }

    /**
     * @param CreateOptionsEvent $event
     */
    public function createSalutationGroups(CreateOptionsEvent $event)
    {
        $this->getSalutationGroups($event->getDataContainer(), $event->getOptions());
    }

    /**
     * Get a list of salutation groups.
     *
     * @param DC_General $dc
     *
     * @param array      $options
     *
     * @return array
     * @SuppressWarnings(PHPMD.ShortVariable)
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getSalutationGroups($dc, $options = array())
    {
        if ($dc instanceof DcCompat && $dc->getModel()) {
            $salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
            $salutationGroups = $salutationGroupRepository->findAll();

            foreach ($salutationGroups as $salutationGroup) {
                $options[$salutationGroup->getId()] = $salutationGroup->getTitle();
            }
            return $options;
        }

        return array();
    }
}
