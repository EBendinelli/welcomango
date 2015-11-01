<?php

namespace Welcomango\Bundle\ParticipationBundle\Twig;

use Welcomango\Model\User;

/**
 * Class DateDifferenceExtension
 */
class DateDifferenceExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('date_difference', array($this, 'dateDifference'))
        );
    }

    /**
     * @param mixed $role
     *
     * @return string
     */
    public function dateDifference($startDate, $endDate)
    {
        $diff = $startDate->diff($endDate);
        $duration = $diff->format('%H');
        return $duration.'h';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date_difference_extension';
    }
}
