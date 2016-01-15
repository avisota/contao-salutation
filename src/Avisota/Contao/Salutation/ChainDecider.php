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

namespace Avisota\Contao\Salutation;

use Avisota\Contao\Entity\Salutation;
use Avisota\Recipient\RecipientInterface;

class ChainDecider implements DeciderInterface
{
    /**
     * @var DeciderInterface[]
     */
    protected $deciders = array();

    public function accept(RecipientInterface $recipient, Salutation $salutation)
    {
        foreach ($this->deciders as $decider) {
            if (!$decider->accept($recipient, $salutation)) {
                return false;
            }
        }
        return true;
    }

    public function addDecider(DeciderInterface $decider)
    {
        $this->deciders[spl_object_hash($decider)] = $decider;
    }

    public function removeDecider(DeciderInterface $decider)
    {
        unset($this->deciders[spl_object_hash($decider)]);
    }
}
