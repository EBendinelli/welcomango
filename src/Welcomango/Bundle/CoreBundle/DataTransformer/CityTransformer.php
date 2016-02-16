<?php

namespace Welcomango\Bundle\CoreBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

use Welcomango\Model\City;

/**
 * Class CityTransformer
 */
class CityTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Transforms an object (company) to a string (number).
     *
     * @param  City|null $city
     *
     * @return string
     */
    public function transform($city)
    {

        if (null === $city) {
            return "";
        }

        return (string) $city->getName().', '.$city->getCountry()->getName();
    }

    /**
     * Transforms a string (id) to an object (City).
     *
     * @param  string $id
     *
     * @throws TransformationFailedException if object (city) is not found.
     *
     * @return Issue|null
     *
     */
    public function reverseTransform($cityName)
    {
        if (!$cityName) {
            return null;
        }

        $cityName = str_replace(' ', '', $cityName);
        $cityExploded = explode(',', $cityName);

        $country = $this->objectManager->getRepository('Welcomango\Model\Country')->findOneByName($cityExploded[1]);

        $city = $this->objectManager->getRepository('Welcomango\Model\City')->findOneBy(['name' => $cityExploded[0], 'country' => $country]);

        if (null === $city) {
            throw new TransformationFailedException(sprintf(
                'Le problème avec l\'id "%s" ne peut pas être trouvé!',
                $cityName
            ));
        }

        return $city;
    }
}
