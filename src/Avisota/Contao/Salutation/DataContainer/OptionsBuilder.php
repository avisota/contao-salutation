<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Salutation\DataContainer;

use Avisota\Contao\Entity\SalutationGroup;
use Avisota\Contao\Core\Event\CollectStylesheetsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use DcGeneral\Contao\Compatibility\DcCompat;
use DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OptionsBuilder implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'avisota.create-salutation-group-options'            => 'createSalutationGroups',
		);
	}

	public function createSalutationGroups(CreateOptionsEvent $event)
	{
		$this->getSalutationGroups($event->getDataContainer(), $event->getOptions());
	}

	/**
	 * Get a list of salutation groups.
	 *
	 * @param DC_General $dc
	 */
	public function getSalutationGroups($dc, $options = array())
	{
		if ($dc instanceof DcCompat && $dc->getModel()) {
			$salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
			/** @var SalutationGroup[] $salutationGroups */
			$salutationGroups = $salutationGroupRepository->findAll();

			foreach ($salutationGroups as $salutationGroup) {
				$options[$salutationGroup->getId()] = $salutationGroup->getTitle();
			}
			return $options;
		}

		return array();
	}
}
