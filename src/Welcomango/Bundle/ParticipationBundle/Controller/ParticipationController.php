<?php

namespace Welcomango\Bundle\ParticipationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Participation;

/**
 * Class ParticipationController
 *
 * @ParamConverter("participation", options={"id" = "participation_id"})
 */
class ParticipationController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/participation/list", name="participation_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $participations = $this->getRepository('Welcomango\Model\Participation')->findByUser($user);

        return array(
            'participations' => $participations,
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/participation/create", name="participation_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $participation = new Participation();

        $form = $this->createForm($this->get('welcomango.form.participation.create'), $participation);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            $this->addFlash('success', $this->trans('participation.created.success', array(), 'participation'));

            return $this->redirect($this->generateUrl('participation_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Participation $participation
     *
     * @Route("/participation/{participation_id}/edit", name="participation_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Participation $participation)
    {
        $form = $this->createForm($this->get('welcomango.form.participation.edit'), $participation);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($participation);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('participation.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('participation_list'));
        }

        return array(
            'form'           => $form->createView(),
            'requested_participation' => $participation
        );
    }

    /**
     * @param Participation $participation
     *
     * @Route("/participation/{participation_id}/delete", name="participation_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Participation $participation)
    {

        $this->getDoctrine()->getManager()->remove($participation);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('participation_list'));
    }

}
