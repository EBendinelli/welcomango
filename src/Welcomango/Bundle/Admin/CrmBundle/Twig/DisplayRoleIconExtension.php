<?php

namespace Welcomango\Bundle\Admin\CrmBundle\Twig;

use Welcomango\Model\User;

/**
 * Class DisplayRoleIconExtension
 */
class DisplayRoleIconExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display_role_icon', array($this, 'displayRoleIcon'), array('is_safe' => array('html')))
        );
    }

    /**
     * @param mixed $role
     *
     * @return string
     */
    public function displayRoleIcon($role)
    {
        switch ($role) {
            case User::ROLE_SUPER_ADMIN:
                $icon = '<i class="fa fa-2x fa-user-secret" title="' . $role . '" data-toggle="tooltip" data-placement="top"></i>';
                break;
            case User::ROLE_ADMIN:
                $icon = '<i class="fa fa-2x fa-user-plus" title="' . $role . '" data-toggle="tooltip" data-placement="top"></i>';
                break;
            case User::ROLE_USER:
                $icon = '<i class="fa fa-2x fa-user" title="' . $role . '" data-toggle="tooltip" data-placement="top"></i>';
                break;
            default:
                $icon = '<i class="fa fa-2x fa-user" title="' . $role . '" data-toggle="tooltip" data-placement="top"></i>';
        }

        return $icon;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'display_role_icon_extension';
    }
}
