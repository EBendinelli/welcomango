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

        return (string) $city->getId().';'.$city->getName();
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
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $city = $this->objectManager->getRepository('Welcomango\Model\City')->findOneById($id);

        if (null === $city) {
            throw new TransformationFailedException(sprintf(
                'Le problème avec l\'id "%s" ne peut pas être trouvé!',
                $id
            ));
        }

        return $city;
    }
}
