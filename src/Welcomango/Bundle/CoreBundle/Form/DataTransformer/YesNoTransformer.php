<?php

namespace Welcomango\Bundle\CoreBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * YesNoTransformer
 */
class YesNoTransformer implements DataTransformerInterface
{
    /**
     * Transform a boolean to an integer
     *
     * @param boolean $value
     *
     * @return integer
     */
    public function transform($value)
    {
        if ($value === true) {
            return 1;
        }

        if ($value === false) {
            return 0;
        }

        if ($value === 2) {
            return 2;
        }

        return $value;
    }

    /**
     * Transform an integer to a boolean
     *
     * @param integer $value
     *
     * @return boolean|null
     */
    public function reverseTransform($value)
    {
        if ($value === 1) {
            return true;
        }

        if ($value === 2) {
            return 2;
        }

        if ($value !== null && $value === 0) {
            return false;
        }

        return null;
    }
}
