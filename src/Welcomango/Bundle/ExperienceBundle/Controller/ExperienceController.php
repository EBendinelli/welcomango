<?php

namespace Welcomango\Bundle\ExperienceBundle\Controller;

use Symfony\Component\HttpFoundation\File\File;
use Gaufrette\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
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

use Symfony\Component\Validator\Constraints\DateTime;
use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Availability;
use Welcomango\Model\Experience;
use Welcomango\Model\Booking;
use Welcomango\Model\Media;

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
            9,
            ['wrap-queries' => true]
        );

        $entityManager = $this->getDoctrine()->getManager();
        if (isset($filters['tags'])) {
            foreach ($filters['tags'] as $tag) {
                $entityManager->persist($tag);
            }
        }

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

        //If there are experiences with updated status we set it back to false as the user will see it here
        $update = false;
        foreach($experiences as $experience){
            if($experience->hasUpdatedStatus()){
                $experience->setUpdatedStatus(False);
                $this->getDoctrine()->getManager()->persist($experience);
                $update = true;
            }
        }
        if($update){
            $this->getDoctrine()->getManager()->flush();
        }

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

        $experience = new Experience();
        $experience->setCreator($user);
        $experience->setPublicationStatus('pending');

        //Set availabilities
        $availabilities = new ArrayCollection();
        $availability   = new Availability();
        $availability->setDay(array('0', '1', '3', '4'));
        $availability->setHour(array('1', '3'));
        $availability->setExperience($experience);
        $availabilities->add($availability);
        $experience->setAvailabilities($availabilities);

        $em = $this->getDoctrine()->getManager();
        $experience->setMedias(new ArrayCollection());
        $form = $this->createForm($this->get('welcomango.form.experience.create'), $experience);
        $experience->setMedias(new ArrayCollection());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $availabilityManager = $this->get('welcomango.front.availability.manager');
            $availabilityManager->generateAvailabilityForExperience($experience, $form);
            $em = $this->getDoctrine()->getManager();
            $em->persist($experience);
            $em->flush();
            $experience->setMedias($this->get('welcomango.media.manager')->generateMediasFromCsv($form->get('medias_upload')->getData(), $experience));
            $em->persist($experience);
            $em->flush();

            return $this->render('WelcomangoExperienceBundle:Experience:createSuccess.html.twig', array(
                'experience' => $experience,
            ));
        }

        return $this->render('WelcomangoExperienceBundle:Experience:create.html.twig', array(
            'form'   => $form->createView(),
            'medias' => new ArrayCollection(),
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
        if($this->getUser() != $experience->getCreator()){
            throw new AccessDeniedException('This user cannot edit this experience.');
        }

        //Here we transform the hour and day data to arrays
        $availabilities      = $experience->getAvailabilities();
        $availabilityManager = $this->get('welcomango.front.availability.manager');
        $availabilityManager->prepareAvailabilityForForm($availabilities);


        $originalAvailabilities = new ArrayCollection();
        foreach ($availabilities as $availability) {
            $originalAvailabilities->add($availability);
        }

        //Experience before edition
        $oldExperience = clone $experience;


        $form = $this->createForm($this->get('welcomango.form.experience.create'), $experience);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if($experience->getPublicationStatus() == 'refused'){
                $experience->setPublicationStatus('pending');
            }

            $availabilityManager->updateAvailabilityForExperience($experience, $form, $originalAvailabilities);
            if($form->get('title')->getData() != $oldExperience ->getTitle() || $form->get('description')->getData() != $oldExperience ->getDescription() || $form->get('city')->getData() != $oldExperience ->getCity()){
                $experience->setPublicationStatus('pending');
            }
            $newMedias = $this->get('welcomango.media.manager')->generateMediasFromCsv($form->get('medias_upload')->getData(), $experience);
            $experience->setMedias($newMedias);


            // This cleanly remove the deleted availabilities
            foreach ($originalAvailabilities as $originalAvailability) {
                if (false === $form->getData()->getAvailabilities()->contains($originalAvailability)) {
                    $this->getDoctrine()->getManager()->remove($originalAvailability);
                }
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('experience.edit.success', array(), 'crm'));

            if($experience->getPublicationStatus() == 'pending'){
                return $this->redirect($this->generateUrl('front_experience_profile_list'));
            }

            return $this->redirect($this->generateUrl('front_experience_edit', array(
                'experience_id'        => $experience->getId(),
                'requested_experience' => $experience,
            )));
        }

        return array(
            'form'       => $form->createView(),
            'experience' => $experience,
            'medias'     => $experience->getMedias(),
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
        $experienceRepository = $this->getRepository('Welcomango\Model\Experience');

        //TODO: We might be able to do better...
        // Check that the experience is still available and not deleted
        if ($experience->isDeleted()) {
            return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig', array(
                'title'          => 'This experience is not available anymore',
                'message'        => 'Well, maybe it never existed...',
                'return_path'    => $this->get('router')->generate('front_experience_list'),
                'return_message' => 'Return to experiences',
            ));
        }
        if ($experience->getPublicationStatus() == 'pending') {
            return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig', array(
                'title'          => 'This experience has not been approved yet.',
                'message'        => 'Just wait a moment until we validate it',
                'return_path'    => $this->get('router')->generate('front_experience_list'),
                'return_message' => 'Return to experiences',
                'icon'           => 'fa-clock-o',
            ));
        }

        $user = $this->getUser();

        //Get Comments
        $feedbacks = $experienceRepository->getCommentsForExperience($experience);

        // TODO: Must create a related experience function
        $relatedExperiences = $experienceRepository->getFeatured(3);

        //Get forbidden dates for datepicker
        $forbiddenDates = $this->get('welcomango.front.experience.manager')->getForbiddenDatesForDatePicker($experience);
        $availablePeriodsPerDate = $this->get('welcomango.front.experience.manager')->getAvailablePeriodPerDate($experience);


        // Prepare the booking form
        $booking = new Booking();
        $booking->setUser($user);
        $booking->setExperience($experience);
        $form = $this->createForm($this->get('welcomango.form.booking.type'), $booking, [
            'available_status' => $this->container->getParameter('available_status'),
            'meeting_times'    => $this->container->getParameter('meeting_times'),
            'experience'       => $experience,
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
                    'title'          => 'Hm. Want to go on an adventure with yourself? ',
                    'message'        => 'Well maybe you just wanted to edit your experience',
                    'return_path'    => $this->get('router')->generate('front_experience_view', array('experience_id' => $experience->getId())),
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
            'forbiddenDates'     => $forbiddenDates,
            'availablePeriodsPerDate' => $availablePeriodsPerDate,
            'feedbacks'          => $feedbacks,
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

        foreach ($availabilities as $availability) {
            $entityManager->remove($availability);
        }

        $entityManager->merge($experience);
        $entityManager->flush();

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
                $data[] = ['id' => $city['id'], 'text' => $city['text'].', '.$city['countryName']];
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

