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

namespace Avisota\Contao\Salutation\DataContainer;

/**
 * Class Salutation
 *
 * @package Avisota\Contao\Salutation\DataContainer
 */
class Salutation
{
    /**
     * Add the type of content element
     *
     * @param array
     *
     * @return string
     */
    public function addElement($contentData)
    {
        return sprintf(
            '<div>%s</div>' . "\n",
            $contentData['salutation']
        );
    }
}
