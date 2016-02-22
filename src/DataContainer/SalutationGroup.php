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

use Avisota\Contao\Core\Service\SuperglobalsService;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class SalutationGroup
 *
 * @package Avisota\Contao\Salutation\DataContainer
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 */
class SalutationGroup extends \Controller
{
    public function generate()
    {
        global $container,
               $AVISOTA_SALUTATION;

        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container['event-dispatcher'];
        /** @var SuperglobalsService $superglobals */
        $superglobals = $container['avisota.superglobals'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('avisota_salutation')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_salutation_group')
        );

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $container['doctrine.orm.entityAccessor'];

        $predefinedSalutations = $AVISOTA_SALUTATION;

        $entityManager = EntityHelper::getEntityManager();

        $salutationGroup = new \Avisota\Contao\Entity\SalutationGroup();
        $salutationGroup->setTitle('Default group generated at ' . date(\Config::get('datimFormat')));
        $salutationGroup->setAlias(null);

        $sorting = 64;
        foreach ($predefinedSalutations as $index => $predefinedSalutation) {
            $salutation = new \Avisota\Contao\Entity\Salutation();

            $entityAccessor->setProperties($salutation, $predefinedSalutation);

            $salutation->setSalutation($superglobals->getLanguage('avisota_salutation/' . $index));
            $salutation->setSalutationGroup($salutationGroup);
            $salutation->setSorting($sorting);
            $salutationGroup->addSalutation($salutation);
            $sorting *= 2;
        }

        $entityManager->persist($salutationGroup);
        $entityManager->flush($salutationGroup);

        $sessionConfirm = \Session::getInstance()->get('TL_CONFIRM');
        if (!is_array($sessionConfirm)) {
            $sessionConfirm = (array) $sessionConfirm;
        }
        $sessionConfirm[] = $superglobals->getLanguage('orm_avisota_salutation_group/group_generated');
        \Session::getInstance()->set('TL_CONFIRM', $sessionConfirm);

        $this->redirect('contao/main.php?do=avisota_salutation');
    }
}
