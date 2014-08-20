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

class RequiredFieldsDecider implements DeciderInterface
{
	public function accept(RecipientInterface $recipient, Salutation $salutation)
	{
		$requiredFields = $salutation->getRequiredFieldsFilter();
		if (!$salutation->getEnableRequiredFieldsFilter() || empty($requiredFields)) {
			return true;
		}

		$details = $recipient->getDetails();
		foreach ($requiredFields as $requiredField) {
			if (empty($details[$requiredField])) {
				/** @var SynonymizerService $synonymizer */
				$synonymizer = $GLOBALS['container']['avisota.recipient.synonymizer'];
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
