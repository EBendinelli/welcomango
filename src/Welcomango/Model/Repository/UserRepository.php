<?php

namespace Welcomango\Model\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 */

class UserRepository extends EntityRepository
{

    public function test(){
        return 'ok';
    }
}