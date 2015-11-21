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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Experience;
use Welcomango\Model\Booking;

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
     * @Route("/profile/experiences", name="front_experience_profile_list")
     * @Template()
     *
     * @return array
     */
    public function profileListAction(Request $request)
    {
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $experiences = $user->getExperiences();

        return array(
            'experiences' => $experiences,
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
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $experience = new Experience(); // Your form data class. Has to be an object, won't work properly with an array.
        $experience->setCreator($user);

        $flow = $this->get('welcomango.form.flow.experience'); // must match the flow's service id
        $flow->bind($experience);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                // flow finished
                $em = $this->getDoctrine()->getManager();
                $em->persist($experience);
                $em->flush();

                $availabilityManager = $this->get('welcomango.front.availability.manager');
                $availabilityManager->generateAvailabilityForExperience($experience, $form);

                $flow->reset(); // remove step data from the session

                return $this->render('WelcomangoExperienceBundle:Experience:createSuccess.html.twig', array(
                    'experience' => $experience,
                ));
            }
        }

        return $this->render('WelcomangoExperienceBundle:Experience:create.html.twig', array(
            'form' => $form->createView(),
            'flow' => $flow,
        ));
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

            return $this->redirect($this->generateUrl('front_experience_edit', array(
                'experience_id'        => $experience->getId(),
                'requested_experience' => $experience,
            )));
        }

        return array(
            'form'           => $form->createView(),
            'experience' => $experience,
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
        //TODO: We might be able to do better...
        // Check that the experience is still available and not deleted
        if($experience->isDeleted()) {
            return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig', array(
                'title' => 'This experience is not available anymore',
                'message' => 'Well, maybe it never existed...',
                'return_path' => $this->get('router')->generate('front_experience_list'),
                'return_message' => 'Return to experiences',
            ));
        }

        $user = $this->getUser();

        // TODO: Must create a related experience function
        $relatedExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->getFeatured(3);

        //Create an array with the forbidden dates
        $forbiddenDates = array();
        $availabilities = $experience->getAvailabilities();
        foreach($availabilities as $availability){

        }

        // Prepare the booking form
        $booking = new Booking();
        $booking->setUser($user);
        $booking->setExperience($experience);
        $form = $this->createForm($this->get('welcomango.form.booking.type'), $booking, [
            'available_status' => $this->container->getParameter('available_status'),
            'meeting_times' => $this->container->getParameter('meeting_times'),
            'experience' => $experience,
        ]);
        $form->handleRequest($request);

        $formSubmitted = false;
        if ($form->isSubmitted()) {
            $formSubmitted = true;
        }

        if ($form->isValid()) {
            $message = $form->get('message')->getData();

            // If the user is trying to book his own experience
            if ($user == $experience->getCreator()) {
                return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig', array(
                    'title' => 'Hm. Want to go on an adventure with yourself? ',
                    'message' => 'Well maybe you just wanted to edit your experience',
                    'return_path' => $this->get('router')->generate('front_experience_view', array('experience_id' => $experience->getId())),
                    'return_message' => 'Return to experience',
                ));
            }

            // If the user is trying to book an experience that is not available
            $bookingManager = $this->get('welcomango.front.booking.manager');
            if (!$bookingManager->processBookingQuery($booking, $form)) {
                return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig', array(
                    'title'          => 'Oops, something went wrong.',
                    'message'        => 'This experience is not available at this time... Try another time or another day',
                    'return_path'    => $this->get('router')->generate('front_experience_view', array('experience_id' => $experience->getId())),
                    'return_message' => 'Return to experience',
                ));
            }

            // Save the request
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            if (null !== $message) {
                $this->get('welcomango.message.creator')->createThread($booking, $user, $booking->getExperience()->getCreator(), $message);
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

        $entityManager = $this->getDoctrine()->getManager();

        $experience->setDeleted(true);
        $availabilities = $experience->getAvailabilities();
        foreach($availabilities as $availability){
            $entityManager->remove($availability);
        }

        $entityManager->merge($experience);
        $entityManager ->flush();

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
