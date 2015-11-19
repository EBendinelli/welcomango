<?php

namespace Welcomango\Bundle\AvailabilityBundle\Manager;


class AvailabilityManager
{
    public function __construct()
    {

    }

    //When creating an experience, process the form to generate the availability and Booking
    public function generateParticipationForExperience($experience, $form){
        if($form->get('always_available')->getData()){

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

