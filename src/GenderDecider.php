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
 * Class GenderDecider
 *
 * @package Avisota\Contao\Salutation
 */
class GenderDecider implements DeciderInterface
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

        $fieldValue = $salutation->getGenderFilter();
        if (!$salutation->getEnableGenderFilter() || empty($fieldValue)) {
            return true;
        }

        $details   = $recipient->getDetails();
        $fieldName = 'gender';

        if (isset($details[$fieldName]) && $fieldValue == $details[$fieldName]) {
            return true;
        }

        /** @var SynonymizerService $synonymizer */
        $synonymizer = $container['avisota.recipient.synonymizer'];
        $synonyms    = $synonymizer->findSynonyms('gender');

        // try synonyms
        if ($synonyms) {
            foreach ($synonyms as $synonym) {
                if (isset($details[$synonym]) && $fieldValue == $details[$synonym]) {
                    return true;
                }
            }
        }

        return false;
    }
}
