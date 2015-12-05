<?php

namespace Welcomango\Bundle\ExperienceBundle\Manager;

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
        $availabilities = $form->get('availabilities')->getData();
        foreach($availabilities as $availability){
            $availability->setExperience($experience);
            $availability->setMonth('*');
            $hours = $availability->getHour();
            //First case is to handle if always available
            if(isset($hours[0]) && $hours[0] == 6){
                $availability->setHour('*');
            }else{
                $hours = $this->generateAvailabilityHours($hours);
                $availability->setHour($hours);
            }

            $days = $availability->getDay();
            //First case is to handle if always available
            if($days[0] == 7){
                $availability->setDay('*');
            }else{
                $days = ','.implode(',', $availability->getDay()).',';
                $availability->setDay($days);
            }
            $this->entityManager->persist($availability);
        }
    }

    //When updating an experience, process the form to update the availabilities
    public function updateAvailabilityForExperience($experience, $form){
        $availabilities = $form->get('availabilities')->getData();
        foreach($availabilities as $availability){
            $availability->setExperience($experience);
            $availability->setMonth('*');
            $hours = $availability->getHour();
            //First case is to handle if always available

            if((isset($hours[0]) && $hours[0] == 6) || empty($hours)){
                $availability->setHour('*');
            }else{
                $hours = $this->generateAvailabilityHours($hours);
                $availability->setHour($hours);
            }


            $days = $availability->getDay();
            //First case is to handle if always available
            if(( isset($days[0]) && $days[0] == 7) || empty($days)){
                $availability->setDay('*');
            }else{
                $days = ','.implode(',', $availability->getDay()).',';
                $availability->setDay($days);
            }
            $this->entityManager->persist($availability);
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

    public function prepareAvailabilityForForm($availabilities){
        foreach($availabilities as $availability){
            $hours = $availability->getHour();
            $days = $availability->getDay();
            $hours = explode(',',$hours);
            $days = explode(',',$days);

            if($days[0] == '*'){
                $daysArray = array('7');
                $days = $daysArray;
            }
            $availability->setDay($days);

            $hoursArray = array();
            if($hours[0] == '*'){
                $hoursArray[] = '6';
            }else{
                foreach($hours as $hour){
                    if(($hour == 6 || $hour == 7 || $hour == 8) && !isset($hoursArray[0])){
                        $hoursArray[0] = 0;
                    }elseif(($hour == 9 || $hour == 10 || $hour == 11) && !isset($hoursArray[1])){
                        $hoursArray[1] = 1;
                    }elseif(($hour == 12 || $hour == 13 || $hour == 14) && !isset($hoursArray[2])){
                        $hoursArray[2] = 2;
                    }elseif(($hour == 15 || $hour == 16 || $hour == 17) && !isset($hoursArray[3])){
                        $hoursArray[3] = 3;
                    }elseif(($hour == 18 || $hour == 19 || $hour == 20) && !isset($hoursArray[4])){
                        $hoursArray[4] = 4;
                    }elseif(($hour == 21 || $hour == 22 || $hour == 23) && !isset($hoursArray[5])){
                        $hoursArray[5] = 5;
                    }
                }
            }
            $hours = $hoursArray;
            $availability->setHour($hours);
        }
    }

}

