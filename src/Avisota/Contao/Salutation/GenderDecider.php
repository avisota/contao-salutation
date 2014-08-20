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

use Avisota\Contao\Core\Recipient\SynonymizerService;
use Avisota\Contao\Entity\Salutation;
use Avisota\Recipient\RecipientInterface;

class GenderDecider implements DeciderInterface
{
	public function accept(RecipientInterface $recipient, Salutation $salutation)
	{
		$fieldValue = $salutation->getGenderFilter();
		if (!$salutation->getEnableGenderFilter() || empty($fieldValue)) {
			return true;
		}

		$details      = $recipient->getDetails();
		$fieldName = 'gender';

		if (isset($details[$fieldName]) && $fieldValue == $details[$fieldName]) {
			return true;
		}

		/** @var SynonymizerService $synonymizer */
		$synonymizer = $GLOBALS['container']['avisota.recipient.synonymizer'];
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
