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

namespace Avisota\Contao\Salutation;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Core\Event\CreateRecipientSourceEvent;
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			CoreEvents::CREATE_RECIPIENT_SOURCE => 'injectSalutation',
		);
	}

	public function injectSalutation(CreateRecipientSourceEvent $event)
	{
		$salutationGroupId         = $event->getConfiguration()->getSalutation();

		if ($salutationGroupId) {
			$salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
			$salutationGroup           = $salutationGroupRepository->find($salutationGroupId);

			if ($salutationGroup) {
				$recipientSource = new SalutationRecipientSource($event->getRecipientSource(), $salutationGroup);
				$event->setRecipientSource($recipientSource);
			}
		}
	}
}
