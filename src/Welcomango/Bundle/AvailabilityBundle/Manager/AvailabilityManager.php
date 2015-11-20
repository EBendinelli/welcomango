<?php

namespace Welcomango\Bundle\AvailabilityBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Welcomango\Model\Availability;

class AvailabilityManager
{

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }

    //When creating an experience, process the form to generate the availability and Booking
    public function generateAvailabilityForExperience($experience, $form){
        if($form->get('availability')->getData() == 0){
            $availability = new Availability();
            $availability->setHour('*');
            $availability->setDay('*');
            $availability->setMonth('*');
            $availability->setExperience($experience);
            $availability->setStartDate($form->get('start_date')->getData());
            $availability->setEndDate($form->get('end_date')->getData());

            $this->entityManager->persist($availability);
            $this->entityManager->flush();
        }else{
            $availability = new Availability();

            $hours = $this->generateAvailabilityHours($form->get('hour')->getData());
            $days = ','.implode(',', $form->get('day')->getData()).',';
            $availability->setHour($hours);
            $availability->setDay($days);
            $availability->setMonth('*');
            $availability->setExperience($experience);
            $availability->setStartDate($form->get('start_date')->getData());
            $availability->setEndDate($form->get('end_date')->getData());

            $this->entityManager->persist($availability);
            $this->entityManager->flush();
        }
    }

    public function generateAvailabilityHours($periods){
        $hours = array();

        foreach($periods as $period) {
            switch ($period) {
                case '0':
                case 'Early Morning':
                    $hours = \array_merge($hours, array('6', '7', '8'));
                    break;
                case '1':
                case 'Morning':
                    $hours = \array_merge($hours, array('9', '10', '11'));
                    break;
                case '2':
                case 'Lunchtime':
                    $hours = \array_merge($hours, array('12', '13', '14'));
                    break;
                case '3':
                case 'Afternoon':
                    $hours = \array_merge($hours, array('15', '16', '17'));
                    break;
                case '4':
                case 'Evening':
                    $hours = \array_merge($hours, array('18', '19', '20'));
                    break;
                case '5':
                case 'Night':
                    $hours = \array_merge($hours, array('21', '22', '23'));
                    break;
            }
        }

        return ','.\implode(',', $hours).',';
    }

}

