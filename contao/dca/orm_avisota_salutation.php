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

/**
 * Table orm_avisota_salutation
 * Entity Avisota\Contao:Salutation
 */
$GLOBALS['TL_DCA']['orm_avisota_salutation'] = array
(
    // Entity
    'entity'          => array(
        'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
    ),
    // Config
    'config'          => array
    (
        'dataContainer'    => 'General',
        'enableVersioning' => true,
    ),
    // DataContainer
    'dca_config'      => array
    (
        'data_provider'  => array
        (
            'default' => array
            (
                'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider',
                'source' => 'orm_avisota_salutation'
            ),
            'parent'  => array
            (
                'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider',
                'source' => 'orm_avisota_salutation_group'
            )
        ),
        'childCondition' => array(
            array(
                'from'   => 'orm_avisota_salutation_group',
                'to'     => 'orm_avisota_salutation',
                'setOn'  => array
                (
                    array(
                        'to_field'   => 'salutationGroup',
                        'from_field' => 'id',
                    ),
                ),
                'filter' => array
                (
                    array
                    (
                        'local'     => 'salutationGroup',
                        'remote'    => 'id',
                        'operation' => '=',
                    )
                )
            )
        )
    ),
    // List
    'list'            => array
    (
        'sorting'           => array
        (
            'mode'                  => 4,
            'fields'                => array('sorting'),
            'panelLayout'           => 'filter;search,limit',
            'headerFields'          => array('title'),
            'child_record_callback' => array('Avisota\Contao\Salutation\DataContainer\Salutation', 'addElement')
        ),
        'label'             => array
        (
            'fields' => array('title'),
            'format' => '%s'
        ),
        'global_operations' => array
        (/*
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
			*/
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'copy'   => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['copy'],
                'icon'       => 'copy.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ),
            'cut'    => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['cut'],
                'href'       => 'act=paste&amp;mode=cut',
                'icon'       => 'cut.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' =>
                    'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) ' .
                    'return false; Backend.getScrollOffset();"'
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
            // TODO pastenew add the id
            // TODO cut add description
        ),
    ),
    // Palettes
    'metapalettes'    => array
    (
        'default' => array
        (
            'salutation' => array('salutation'),
            'filter'     => array('enableGenderFilter', 'enableRequiredFieldsFilter')
        ),
    ),
    'metasubpalettes' => array
    (
        'enableGenderFilter'         => array('genderFilter'),
        'enableRequiredFieldsFilter' => array('requiredFieldsFilter'),
    ),
    // Fields
    'fields'          => array
    (
        'id'                         => array(
            'field' => array(
                'id'      => true,
                'type'    => 'string',
                'length'  => '36',
                'options' => array('fixed' => true),
            )
        ),
        'createdAt'                  => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'create')
            )
        ),
        'updatedAt'                  => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'update')
            )
        ),
        'salutationGroup'            => array(
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['group'],
            'eval'      => array(
                'doNotShow' => true,
            ),
            'manyToOne' => array(
                'index'        => true,
                'targetEntity' => 'Avisota\Contao\Entity\SalutationGroup',
                'cascade'      => array('persist', 'detach', 'merge', 'refresh'),
                'inversedBy'   => 'salutations',
                'joinColumns'  => array(
                    array(
                        'name'                 => 'salutationGroup',
                        'referencedColumnName' => 'id',
                    )
                )
            )
        ),
        'sorting'                    => array
        (
            'field' => array(
                'type' => 'integer'
            )
        ),
        'salutation'                 => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['salutation'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'maxlength'      => 255,
                'mandatory'      => true,
                'decodeEntities' => true,
                'tl_class'       => 'clr'
            ),
        ),
        'enableGenderFilter'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['enableGenderFilter'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true),
        ),
        'genderFilter'               => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['genderFilter'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array('male', 'female'),
            'reference' => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['gender'],
            'eval'      => array('mandatory' => true, 'includeBlankOption' => true),
            'field'     => array('nullable' => true)
        ),
        'enableRequiredFieldsFilter' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['enableRequiredFieldsFilter'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true),
        ),
        'requiredFieldsFilter'       => array
        (
            'label'            => &$GLOBALS['TL_LANG']['orm_avisota_salutation']['requiredFieldsFilter'],
            'exclude'          => true,
            'inputType'        => 'checkbox',
            'eval'             => array(
                'mandatory' => true,
                'multiple'  => true
            ),
            'field'            => array(
                'nullable' => true
            ),
            'options_callback' =>
                \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
                    'avisota.create-salutation-recipient-field-options',
                    'Avisota\Contao\Core\Event\CreateOptionsEvent'
                ),
        ),
    )
);
