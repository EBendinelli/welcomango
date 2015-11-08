<?php

namespace Welcomango\Bundle\ExperienceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Experience;
use Welcomango\Model\Participation;

/**
 * Class ExperienceController
 *
 * @ParamConverter("experience", options={"id" = "experience_id"})
 */
class ExperienceController extends BaseController
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
        $filters = $this->getFilters(array(), 'experienceSearch');

        $paginator  = $this->get('knp_paginator');
        $query      = $this->getRepository('Welcomango\Model\Experience')->createPagerQueryBuilder($filters);
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            6
        );

        $form = $this->createForm($this->get('welcomango.form.experience.filter'), $filters);

        return array(
            'pagination' => $pagination,
            'form'       => $form->createView(),
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
            'form' => $form->createView(),
        );
    }


    /**
     * @param Request    $request
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
            'requested_user' => $experience,
        );


    }

    /**
     * @param Request    $request
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
        $form = $this->createForm($this->get('welcomango.form.participation.type'), $participation, [
            'available_status' => $this->container->getParameter('available_status'),
        ]);
        $form->handleRequest($request);

        $formSubmitted = false;
        if ($form->isSubmitted()) {
            $formSubmitted = true;
        }

        if ($form->isValid()) {
            $message = $form->get('message')->getData();

            $participationManager = $this->get('welcomango.front.participation.manager');

            $participation = $participationManager->processParticipationQuery($participation, $form);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($participation);
            $entityManager->flush();

            if (null !== $message) {
                $composer = $this->container->get('welcomango.message.composer');

                $message = $composer->newThread()
                    ->setSender($user)
                    ->addRecipient($experience->getAuthor())
                    ->setSubject($experience->getTitle().' - Messages')
                    ->setBody($message)
                    ->setParticipation($participation)
                    ->getMessage();

                $sender = $this->container->get('fos_message.sender');
                $sender->send($message);
            }

            return $this->render('WelcomangoExperienceBundle:Experience:requestSent.html.twig');
        }

        return $this->render('WelcomangoExperienceBundle:Experience:view.html.twig', array(
            'experience'         => $experience,
            'relatedExperiences' => $relatedExperiences,
            'formSubmitted'      => $formSubmitted,
            'form'               => $form->createView(),
        ));

    }

    /**
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/delete", name="front_experience_delete")
     * @ParamConverter("experience", options={"id" = "experience_id"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Experience $experience)
    {
        if (!$this->isGranted('delete', $experience)) {
            return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig');
        }

        $this->getDoctrine()->getManager()->remove($experience);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('WelcomangoExperienceBundle:Experience:deleteSuccess.html.twig');
    }


    /**
     * List cities for Ajax Calls
     *
     * @param Request $request
     *
     * @Route("/json/cities/list.json", name="experience_search_ajax", defaults={"_format"="json"})
     *
     * @return JsonResponse
     */
    public function citiesAction(Request $request)
    {
        $data = array();

        if ($request->request->has('query') && $request->request->get('query') != '') {
            $query  = $request->request->get('query');
            $cities = $this->getRepository('Welcomango\Model\City')->findForAutocomplete($query);

            foreach ($cities as $city) {
                $data[] = ['id' => $city['id'], 'text' => $city['text']];
            }
        }

        return new JsonResponse($data);
    }

    /**
     * Process and render form filters
     *
     * @param Request $request
     *
     * @Route("/experience/filters/research", name="experience_filters")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterFormAction(Request $request)
    {
        if ($request->request->has('_reset')) {
            $this->removeFilters('experienceSearch');

            return $this->redirect($this->generateUrl('front_experience_list'));
        }

        $form = $this->createForm($this->get('welcomango.form.experience.filter'), null);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $datas = $form->getData();
            $this->setFilters($datas, 'experienceSearch');
        }

        return $this->redirect($this->generateUrl('front_experience_list'));
    }
}
