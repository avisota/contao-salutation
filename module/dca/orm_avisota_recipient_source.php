<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table orm_avisota_recipient_source
 * Entity Avisota\Contao:RecipientSource
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metapalettes']['csv_file']['details'][]                          = 'salutation';
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metapalettes']['dummy']['details'][]                             = 'salutation';
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metapalettes']['integrated']['details'][]                        = 'salutation';
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metapalettes']['integrated_by_mailing_list']['details'][]        = 'salutation';
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['metapalettes']['integrated_member_by_mailing_list']['details'][] = 'salutation';

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['fields']['salutation'] = array
(
	'default'          => false,
	'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['salutation'],
	'inputType'        => 'select',
	'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
			'avisota.create-salutation-group-options'
		),
	'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true)
);
