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
use Avisota\Contao\Entity\SalutationGroup;
use Avisota\Recipient\RecipientInterface;

/**
 * Class Selector
 *
 * @package Avisota\Contao\Salutation
 */
class Selector
{
    /**
     * @var DeciderInterface
     */
    protected $decider;

    /**
     * Set the salutation decider.
     *
     * @param DeciderInterface $decider
     *
     * @return $this
     */
    public function setDecider(DeciderInterface $decider)
    {
        $this->decider = $decider;
        return $this;
    }

    /**
     * Get the salutation decider.
     *
     * @return DeciderInterface
     */
    public function getDecider()
    {
        return $this->decider;
    }

    /**
     * Select a salutation from group.
     *
     * @param RecipientInterface $recipient
     * @param SalutationGroup    $group
     *
     * @return Salutation|null
     */
    public function selectSalutation(RecipientInterface $recipient, SalutationGroup $group)
    {
        $salutations = $group->getSalutations();

        foreach ($salutations as $salutation) {
            if ($this->decider->accept($recipient, $salutation)) {
                return $salutation;
            }
        }

        return null;
    }
}
