<?php

namespace Welcomango\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WelcomangoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
