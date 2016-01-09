<?php

namespace Welcomango\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Class Controller for low level methods
 */
class Controller extends BaseController
{
    /**
     * Get repository
     *
     * @param string $class class
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($class)
    {
        return $this->getDoctrine()->getManager()->getRepository($class);
    }

    /**
     * Set flash
     *
     * @param string $type type of flash
     * @param string $text text of flash message
     */
    protected function addFlash($type, $text)
    {
        $this->get('session')->getFlashBag()->add($type, $text);
    }

    /**
     * Provides a helper to translate text inside classes
     *
     * @param string $text   The key to translate
     * @param array  $params Array of params such as domain translations
     * @param string $domain Domain where we want the translation
     *
     * @return string
     */
    public function trans($text, $params = array(), $domain = "admin")
    {
        /** @Ignore */
        return $this->get('translator')->trans($text, $params, $domain);
    }

    /**
     * Check if the object is related to site or its parents
     *
     * @param \stdClass $entity
     *
     * @throws AccessDeniedException
     */
    public function checkAccess($entity)
    {
        $siteIds = $this->getSite()->getResolutionManager()->getScopeIds();

        if (!in_array($entity->getSite()->getId(), $siteIds)) {
            throw new AccessDeniedException('Not allowed to edit entity that belongs to another site.');
        }
    }

    /**
     * Store an array of filters in the session
     *
     * @param array  $filters Filters
     * @param string $type    Type
     */
    public function setFilters(array $filters = array(), $type = null)
    {
        foreach ($filters as $key => $value) {
            // Transform entities objects into a pair of class/id
            if (is_object($value)) {
                if ($value instanceof Collection) {
                    if (count($value)) {
                        $filters[$key] = array(
                            'class' => get_class($value->first()),
                            'ids'   => array()
                        );
                        foreach ($value as $v) {
                            $identifier             = $this->getEntityManager()->getUnitOfWork()->getEntityIdentifier($v);
                            $filters[$key]['ids'][] = $identifier['id'];
                        }
                    } else {
                        unset($filters[$key]);
                    }
                } elseif (!$value instanceof \DateTime && !$value instanceof ArrayCollection) {
                    $filters[$key] = array(
                        'class' => get_class($value),
                        'id'    => $value->getId()
                    );
                }
            }
        }

        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $session->set(
            'admin.filters' . ($type ? '_' . $type : ''),
            $filters
        );
    }

    /**
     * Get Filters
     *
     * @param array  $filters Filters
     * @param string $type    Type
     *
     * @return array
     */
    public function getFilters(array $filters = array(), $type = null)
    {
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();
        $filters = array_merge(
            $filters,
            $session->get(
                'admin.filters' . ($type ? '_' . $type : ''),
                array()
            )
        );

        foreach ($filters as $key => $value) {
            // Get entities from pair of class/id
            if (is_array($value) && isset($value['class'])) {
                if (isset($value['id'])) {
                    $filters[$key] = $this->getDoctrine()->getEntityManager()->find($value['class'], $value['id']);
                } elseif (isset($value['ids'])) {
                    $data = $this
                        ->getDoctrine()->getEntityManager()
                        ->getRepository($value['class'])
                        ->findBy(array('id' => $value['ids']));

                    $filters[$key] = new ArrayCollection($data);
                }
            }
        }

        return $filters;
    }

    /**
     * @param string $type Type
     *
     * @return array
     */
    public function removeFilters($type = null)
    {
        $session = $this->get('request_stack')->getCurrentRequest()->getSession();

        $session->set(
            'admin.filters' . ($type ? '_' . $type : ''),
            array()
        );
    }
}
