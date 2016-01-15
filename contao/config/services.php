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
