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
use Avisota\Recipient\RecipientInterface;

/**
 * Class RequiredFieldsDecider
 *
 * @package Avisota\Contao\Salutation
 */
class RequiredFieldsDecider implements DeciderInterface
{
    /**
     * @param RecipientInterface $recipient
     * @param Salutation         $salutation
     *
     * @return bool
     */
    public function accept(RecipientInterface $recipient, Salutation $salutation)
    {
        global $container;

        $requiredFields = $salutation->getRequiredFieldsFilter();
        if (!$salutation->getEnableRequiredFieldsFilter() || empty($requiredFields)) {
            return true;
        }

        $details = $recipient->getDetails();
        foreach ($requiredFields as $requiredField) {
            if (empty($details[$requiredField])) {
                /** @var SynonymizerService $synonymizer */
                $synonymizer = $container['avisota.recipient.synonymizer'];
                $synonyms    = $synonymizer->findSynonyms($requiredField);
                $stillEmpty  = true;

                if ($synonyms) {
                    foreach ($synonyms as $synonym) {
                        if (!empty($details[$synonym])) {
                            $stillEmpty = false;
                            break;
                        }
                    }
                }

                if ($stillEmpty) {
                    return false;
                }
            }
        }

        return true;
    }
}
