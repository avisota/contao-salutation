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

/** @var \Pimple $container */

$container['avisota.salutation.decider'] = $container->share(
    function () {
        $decider = new \Avisota\Contao\Salutation\ChainDecider();

        foreach ($GLOBALS['AVISOTA_SALUTATION_DECIDER'] as $deciderClass) {
            $decider->addDecider(new $deciderClass());
        }

        return $decider;
    }
);

$container['avisota.salutation.selector'] = $container->share(
    function ($container) {
        $selector = new \Avisota\Contao\Salutation\Selector();
        $selector->setDecider($container['avisota.salutation.decider']);
        return $selector;
    }
);
