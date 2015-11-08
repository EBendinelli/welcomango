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
     * @Route("/requests/received", name="participation_received_list")
     * @Template()
     *
     * @return array
     */
    public function listReceivedAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $experience = $user->getExperience();

        $activeTab = $request->get('display');
        if(!$activeTab) $activeTab = 'received';

        if($activeTab == 'received'){ $statusFilter = array('Requested', 'Accepted', 'Refused'); }
        else{ $statusFilter = array('Happened'); }


        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Participation')->findBy(array('experience' => $experience , 'isParticipant' => 1, 'status' => $statusFilter), array('createdAt' => 'DESC'));
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        return array(
            'participations' => $pagination,
            'activeTab' => $activeTab,
            'user' => $user
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/requests/sent", name="participation_sent_list")
     * @Template()
     *
     * @return array
     */
    public function listSentAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $activeTab = $request->get('display');
        if(!$activeTab) $activeTab = 'sent';

        if($activeTab == 'sent'){ $statusFilter = array('Requested', 'Accepted', 'Refused'); }
        else{ $statusFilter = array('Happened'); }

        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Participation')->findBy(array('user' => $user, 'isParticipant' => 1, 'status' => $statusFilter), array('createdAt' => 'DESC'));
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        return array(
            'participations' => $pagination,
            'activeTab' => $activeTab,
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
     * @Route("/request/update/{participation_id}/status", name="participation_update")
     * @Template()
     *
     * @return array
     */
    public function updateAction(Request $request, Participation $participation)
    {
        $participation->setStatus($request->query->get('status'));

        $this->getDoctrine()->getManager()->persist($participation);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', $this->trans('participation.edit.success', array(), 'crm'));

        return $this->redirect($this->generateUrl('participation_received_list'));
    }

}
