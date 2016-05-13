<?php

namespace Welcomango\Bundle\ExperienceBundle\Twig;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Welcomango\Model\Experience;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManager;


/**
 * Class DisplayExperienceBannerExtension
 */
class DisplayPriceExtension extends \Twig_Extension
{

        /**
         * @var TranslatorInterface $translator
         */
        private $translator;

    /**
     * @var Doctrine\ORM\EntityManager entityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->entityManager   = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('display_price', [$this, 'displayPrice'], ['is_safe' => ['html']])
        );
    }

    /**
     * @param  $experience
     *
     * @return string
     */
    public function displayPrice($experience, $booking = false)
    {
        $price = $experience->getPricePerHour();

        if($price) {
            $session = new Session();
            $currency = $session->get('currency');

            if(!$currency){
                $currencyRepo = $this->entityManager->getRepository('Welcomango\Model\Currency');
                $currency = $currencyRepo->findOneBy(['code' => 'EUR']);
            }

            //If we need to calculate the estimated price
            if($booking){
                $duration = $booking->getEndDatetime()->diff($booking->getStartDatetime());
                $duration = $duration->format("%h");
                $price = $price*$duration;
            }

            //Check the currency of the experience and update the value according to the currency selected by the user
            if ($experience->getCurrency()->getCode() != $currency->getCode()) {
                $price = round($price * $currency->getRate());
            }

            if (trim($currency->getPosition()) == 'a') {
                $price = $price.$currency->getSymbol();
            }else{
                $price = $currency->getSymbol() . $price;
            }

            return $price . '/h';
        }elseif($experience->getContribution()){
            return $this->translator->trans('global.contribution', [],'interface');
        }else{
            return $this->translator->trans('global.free', [],'interface');
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'display_price';
    }
}
