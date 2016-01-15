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

use Avisota\Contao\Entity\RecipientSource;
use Avisota\Contao\Entity\Salutation;
use Contao\Doctrine\ORM\EntityAccessor;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class SalutationGroup
 *
 * @package Avisota\Contao\Salutation\DataContainer
 */
class SalutationGroup extends \Controller
{
    public function generate()
    {
        /** @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('avisota_salutation')
        );
        $eventDispatcher->dispatch(
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
            new LoadLanguageFileEvent('orm_avisota_salutation_group')
        );

        /** @var EntityAccessor $entityAccessor */
        $entityAccessor = $GLOBALS['container']['doctrine.orm.entityAccessor'];

        $predefinedSalutations = $GLOBALS['AVISOTA_SALUTATION'];

        $entityManager = EntityHelper::getEntityManager();

        $salutationGroup = new \Avisota\Contao\Entity\SalutationGroup();
        $salutationGroup->setTitle('Default group generated at ' . date($GLOBALS['TL_CONFIG']['datimFormat']));
        $salutationGroup->setAlias(null);

        $sorting = 64;
        foreach ($predefinedSalutations as $index => $predefinedSalutation) {
            $salutation = new Salutation();

            $entityAccessor->setProperties($salutation, $predefinedSalutation);

            $salutation->setSalutation($GLOBALS['TL_LANG']['avisota_salutation'][$index]);
            $salutation->setSalutationGroup($salutationGroup);
            $salutation->setSorting($sorting);
            $salutationGroup->addSalutation($salutation);
            $sorting *= 2;
        }

        $entityManager->persist($salutationGroup);
        $entityManager->flush($salutationGroup);

        $_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['orm_avisota_salutation_group']['group_generated'];

        $this->redirect('contao/main.php?do=avisota_salutation');
    }
}
