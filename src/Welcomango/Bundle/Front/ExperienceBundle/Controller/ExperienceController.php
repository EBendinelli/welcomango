<?php

namespace Welcomango\Bundle\Front\ExperienceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\Front\CoreBundle\Controller\Controller;
use Welcomango\Model\Experience;
use Welcomango\Model\Participation;


/**
 * Class ExperienceController
 *
 * @ParamConverter("experience", options={"id" = "experience_id"})
 */
class ExperienceController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/experiences", name="front_experience_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Experience')->findAll();
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            6
        );

        return array(
            'pagination' => $pagination,
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/experience/create", name="front_experience_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $experience = new Experience();

        $form = $this->createForm($this->get('welcomango.form.experience.create'), $experience);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($experience);
            $em->flush();

//            $this->getRepository('Welcomango\Model\Experience')->updateExperience($experience);

            $this->addFlash('success', $this->trans('experience.created.success', array(), 'user'));

            return $this->redirect($this->generateUrl('experience_edit', array(
                'experience_id' => $experience->getId(),
            )));
        }

        return array(
            'form' => $form->createView()
        );
    }



    /**
     * @param Request $request
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/edit", name="front_experience_edit")
     * @ParamConverter("experience", options={"id" = "experience_id"})
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Experience $experience)
    {
        $form = $this->createForm($this->get('welcomango.form.experience.edit'), $experience);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($experience);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('experience.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('experience_edit', array(
                'experience_id'        => $experience->getId(),
                'requested_experience' => $experience,
            )));
        }

        return array(
            'form'           => $form->createView(),
            'requested_user' => $experience
        );


    }

    /**
     * @param Request $request
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}", name="front_experience_view")
     * @ParamConverter("experience", options={"id" = "experience_id"})
     * @Template()
     *
     * @return array
     */
    public function viewAction(Request $request, Experience $experience)
    {
        $user = $this->getUser();

        // Must create a related experience function
        $relatedExperiences = $this
        ->getRepository('Welcomango\Model\Experience')
        ->getFeatured(3);

        $participation = new Participation();
        $participation->setUser($user);
        $participation->setExperience($experience);
        $form = $this->createForm($this->get('welcomango.front.form.participation.type'), $participation);
        $form->handleRequest($request);

        $formSubmitted = false;
        if($form->isSubmitted()){
            $formSubmitted = true;
        }

        if ($form->isValid()) {
            $participationManager = $this->get('welcomango.front.participation.manager');

            $participation = $participationManager->processParticipationQuery($participation, $form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->render('WelcomangoFrontExperienceBundle:Experience:requestSent.html.twig');
        }

        return $this->render('WelcomangoFrontExperienceBundle:Experience:view.html.twig', array(
            'experience' => $experience,
            'relatedExperiences' => $relatedExperiences,
            'formSubmitted' => $formSubmitted,
            'form' => $form->createView()
        ));

    }

    /**
     *
     * @Route("/experience/{experience_id}/delete", name="front_experience_delete")
     * @ParamConverter("experience", options={"id" = "experience_id"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Experience $experience)
    {
        $this->getDoctrine()->getManager()->remove($experience);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('experience_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/experience/_experience_search_ajax", name="front_experience_search_ajax")
     * @Method("POST")
     * @Template("YproxAdminCrmBundle:Experience:_experience.html.twig")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query = $request->request->get('query');
        $experiences = $this->getRepository('Welcomango\Model\Experience')->findByQuery($query);

        return array(
            'experience' => $experiences
        );
    }
}
