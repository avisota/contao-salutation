<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-subscription-recipient
 * @license    LGPL-3.0+
 * @filesource
 */

/**
 * Table orm_avisota_message_category
 * Entity Avisota\Contao:MessageCategory
 */
$GLOBALS['TL_DCA']['orm_avisota_message_category']['metapalettes']['default']['salutation'] = array('salutation');

$GLOBALS['TL_DCA']['orm_avisota_message_category']['fields']['salutation'] = array
(
    'default'          => false,
    'label'            => &$GLOBALS['TL_LANG']['orm_avisota_message_category']['salutation'],
    'inputType'        => 'select',
    'eval'             => array(
        'tl_class'           => 'w50',
        'includeBlankOption' => true
    ),
    'options_callback' =>
        \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
            'avisota.create-salutation-group-options',
            'Avisota\Contao\Core\Event\CreateOptionsEvent'
        ),
);
