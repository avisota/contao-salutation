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

namespace Avisota\Contao\Salutation;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Core\Event\CreateFakeRecipientEvent;
use Avisota\Contao\Core\Event\CreatePublicEmptyRecipientEvent;
use Avisota\Contao\Core\Event\CreateRecipientSourceEvent;
use Avisota\Contao\Entity\Message;
use Avisota\Contao\Message\Core\Renderer\TagReplacementService;
use Avisota\Recipient\MutableRecipient;
use Avisota\Recipient\RecipientInterface;
use Contao\Doctrine\ORM\EntityHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            CoreEvents::CREATE_RECIPIENT_SOURCE       => 'injectSalutation',
            CoreEvents::CREATE_FAKE_RECIPIENT         => array('createFakeRecipient', -100),
            CoreEvents::CREATE_PUBLIC_EMPTY_RECIPIENT => array('createPublicEmptyRecipient', -100),
        );
    }

    public function injectSalutation(CreateRecipientSourceEvent $event)
    {
        $salutationGroupId = $event->getConfiguration()->getSalutation();

        if ($salutationGroupId) {
            $salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
            $salutationGroup           = $salutationGroupRepository->find($salutationGroupId);

            if ($salutationGroup) {
                $recipientSource = new SalutationRecipientSource($event->getRecipientSource(), $salutationGroup);
                $event->setRecipientSource($recipientSource);
            }
        }
    }

    public function createFakeRecipient(CreateFakeRecipientEvent $event)
    {
        $recipient = $event->getRecipient();
        $message   = $event->getMessage();

        $this->addSalutationToRecipient($recipient, $message);
    }

    public function createPublicEmptyRecipient(CreatePublicEmptyRecipientEvent $event)
    {
        $recipient = $event->getRecipient();
        $message   = $event->getMessage();

        $this->addSalutationToRecipient($recipient, $message);
    }

    protected function addSalutationToRecipient(RecipientInterface $recipient, Message $message = null)
    {
        if ($recipient->get('salutation') || !$message) {
            return;
        }

        $salutationGroupId = $message->getCategory()->getSalutation();

        if (!$salutationGroupId) {
            return;
        }

        $salutationGroupRepository = EntityHelper::getRepository('Avisota\Contao:SalutationGroup');
        $salutationGroup           = $salutationGroupRepository->find($salutationGroupId);

        if (!$salutationGroup) {
            return;
        }

        /** @var Selector $selector */
        $selector = $GLOBALS['container']['avisota.salutation.selector'];

        /** @var TagReplacementService $tagReplacer */
        $tagReplacer = $GLOBALS['container']['avisota.message.tagReplacementEngine'];

        if (!$recipient instanceof MutableRecipient) {
            $recipient = new MutableRecipient($recipient->getEmail(), $recipient->getDetails());
        }

        $salutation = $selector->selectSalutation($recipient, $salutationGroup);

        if (!$salutation) {
            return;
        }

        $pattern = $salutation->getSalutation();
        $buffer  = $tagReplacer->parse($pattern, $recipient->getDetails());

        $recipient->set('salutation', $buffer);
    }
}
