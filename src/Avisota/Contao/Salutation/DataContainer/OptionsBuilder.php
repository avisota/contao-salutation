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

use Avisota\Contao\Entity\SalutationGroup;
use Avisota\Contao\Core\Event\CollectStylesheetsEvent;
use Contao\Doctrine\ORM\EntityHelper;
use ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\Compatibility\DcCompat;
use ContaoCommunityAlliance\DcGeneral\DC_General;
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
