<?php

namespace Welcomango\Bundle\CoreBundle\Util;

/**
 * Class Util
 */
class Util
{
    /**
     * @static
     *
     * @param string $value
     *
     * @return string
     */
    public static function slugify($value)
    {
        // based on http://cubiq.org/the-perfect-php-clean-url-generator

        setlocale(LC_CTYPE, 'en_EN.UTF8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        $clean = strip_tags($clean);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);

        if (substr($clean, -1) == '-') {
            $clean = substr($clean, 0, -1);
        }

        return $clean;
    }
}
