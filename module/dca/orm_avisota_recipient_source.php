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
 * Table orm_avisota_recipient_source
 * Entity Avisota\Contao:RecipientSource
 */
$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['config']['onload_callback'][] = function (\ContaoCommunityAlliance\DcGeneral\Contao\Compatibility\DcCompat $dc) {
    $dataDefinition     = $dc->getEnvironment()->getDataDefinition();
    $palettesDefinition = $dataDefinition->getPalettesDefinition();
    $palettes           = $palettesDefinition->getPalettes();

    $legend = new \ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Legend('salutation');
    $legend->addProperty(new \ContaoCommunityAlliance\DcGeneral\DataDefinition\Palette\Property('salutation'));

    foreach ($palettes as $palette) {
        $legends = $palette->getLegends();
        $palette->addLegend(clone $legend, count($legends) > 1 ? $legends[1] : null);
    }
};

$GLOBALS['TL_DCA']['orm_avisota_recipient_source']['fields']['salutation'] = array
(
    'default'          => false,
    'label'            => &$GLOBALS['TL_LANG']['orm_avisota_recipient_source']['salutation'],
    'inputType'        => 'select',
    'options_callback' => \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
        'avisota.create-salutation-group-options',
        'Avisota\Contao\Core\Event\CreateOptionsEvent'
    ),
    'eval'             => array('tl_class' => 'w50', 'includeBlankOption' => true)
);
