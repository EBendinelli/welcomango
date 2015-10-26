<?php

namespace Welcomango\Bundle\Front\CrmBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WelcomangoFrontCrmBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
