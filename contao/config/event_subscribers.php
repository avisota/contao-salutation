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

use Avisota\Contao\Salutation\DataContainer\OptionsBuilder;
use Avisota\Contao\Salutation\DataContainer\Salutation;
use Avisota\Contao\Salutation\DataContainer\SalutationGroup;
use Avisota\Contao\Salutation\EventSubscriber;

return array(
    new EventSubscriber(),
    new OptionsBuilder(),
    new Salutation(),
    new SalutationGroup()
);
