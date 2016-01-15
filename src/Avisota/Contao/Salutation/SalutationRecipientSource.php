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

use Avisota\Contao\Core\Recipient\SynonymizerService;
use Avisota\Contao\Entity\Salutation;
use Avisota\Contao\Entity\SalutationGroup;
use Avisota\Contao\Message\Core\Renderer\TagReplacementService;
use Avisota\Recipient\MutableRecipient;
use Avisota\Recipient\RecipientInterface;
use Avisota\RecipientSource\RecipientSourceInterface;

class SalutationRecipientSource implements RecipientSourceInterface
{
    /**
     * @var RecipientSourceInterface
     */
    protected $recipientSource;

    /**
     * @var SalutationGroup
     */
    protected $group;

    function __construct(RecipientSourceInterface $recipientSource, SalutationGroup $group)
    {
        $this->recipientSource = $recipientSource;
        $this->group           = $group;
    }

    /**
     * @return RecipientSourceInterface
     */
    public function getRecipientSource()
    {
        return $this->recipientSource;
    }

    /**
     * @return SalutationGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function countRecipients()
    {
        return $this->recipientSource->countRecipients();
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients($limit = null, $offset = null)
    {
        $recipients = $this->recipientSource->getRecipients($limit, $offset);

        if (count($recipients)) {
            /** @var Selector $selector */
            $selector = $GLOBALS['container']['avisota.salutation.selector'];

            /** @var TagReplacementService $tagReplacer */
            $tagReplacer = $GLOBALS['container']['avisota.message.tagReplacementEngine'];

            foreach ($recipients as $recipient) {
                if ($recipient->get('salutation')) {
                    continue;
                }

                if (!$recipient instanceof MutableRecipient) {
                    $recipient = new MutableRecipient($recipient->getEmail(), $recipient->getDetails());
                }

                $salutation = $selector->selectSalutation($recipient, $this->group);

                if (!$salutation) {
                    continue;
                }

                /** @var SynonymizerService $synonymizer */
                $synonymizer = $GLOBALS['container']['avisota.recipient.synonymizer'];

                $pattern = $salutation->getSalutation();
                $details = $synonymizer->expandDetailsWithSynonyms($recipient);
                $buffer  = $tagReplacer->parse($pattern, $details);

                $recipient->set('salutation', $buffer);
            }
        }

        return $recipients;
    }
}
