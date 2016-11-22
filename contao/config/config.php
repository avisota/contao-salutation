<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-salutation
 * @license    LGPL-3.0+
 * @filesource
 */

/**
 * Static back end modules
 */
$GLOBALS['BE_MOD']['avisota']['avisota_salutation'] = array
(
    'nested'     => 'avisota_config:recipient',
    'tables'     => array('orm_avisota_salutation_group', 'orm_avisota_salutation'),
    'icon'       => 'assets/avisota/salutation/images/salutation.png',
    'stylesheet' => 'assets/avisota/salutation/css/backend.css',
);

/**
 * Entities
 */
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_salutation';
$GLOBALS['DOCTRINE_ENTITIES'][] = 'orm_avisota_salutation_group';

/**
 * Salutation selection decider
 */
$GLOBALS['AVISOTA_SALUTATION_DECIDER'][] = 'Avisota\Contao\Salutation\GenderDecider';
$GLOBALS['AVISOTA_SALUTATION_DECIDER'][] = 'Avisota\Contao\Salutation\RequiredFieldsDecider';

/**
 * Predefined salutations
 */
$GLOBALS['AVISOTA_SALUTATION'][0] = array(
    'enableGenderFilter'         => true,
    'genderFilter'               => 'male',
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('title', 'forename', 'surname'),
);
$GLOBALS['AVISOTA_SALUTATION'][1] = array(
    'enableGenderFilter'         => true,
    'genderFilter'               => 'female',
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('title', 'forename', 'surname'),
);
$GLOBALS['AVISOTA_SALUTATION'][2] = array(
    'enableGenderFilter'         => true,
    'genderFilter'               => 'male',
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('forename', 'surname'),
);
$GLOBALS['AVISOTA_SALUTATION'][3] = array(
    'enableGenderFilter'         => true,
    'genderFilter'               => 'female',
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('forename', 'surname'),
);
$GLOBALS['AVISOTA_SALUTATION'][4] = array(
    'enableGenderFilter'         => true,
    'genderFilter'               => 'male',
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('surname'),
);
$GLOBALS['AVISOTA_SALUTATION'][5] = array(
    'enableGenderFilter'         => true,
    'genderFilter'               => 'female',
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('surname'),
);
$GLOBALS['AVISOTA_SALUTATION'][6] = array(
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('title', 'forename', 'surname')
);
$GLOBALS['AVISOTA_SALUTATION'][7] = array(
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('forename', 'surname')
);
$GLOBALS['AVISOTA_SALUTATION'][8] = array(
    'enableRequiredFieldsFilter' => true,
    'requiredFieldsFilter'       => array('surname')
);
$GLOBALS['AVISOTA_SALUTATION'][9] = array();
