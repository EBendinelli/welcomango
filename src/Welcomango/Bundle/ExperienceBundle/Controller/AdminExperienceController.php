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

/**
 * Class ExperienceController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("experience", options={"id" = "experience_id"})
 */
class AdminExperienceController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/experience/list", name="admin_experience_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $filters   = $this->getFilters(array(), 'admin_experienceSearch');
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Experience')->createPagerQueryBuilder($filters, true);

        $form = $this->createForm($this->get('welcomango.admin.form.experience.filter'), $filters);

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50,
            ['wrap-queries' => true]
        );

        return array(
            'pagination' => $pagination,
            'form'       => $form->createView()
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/experience/create", name="admin_experience_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $experience = new Experience();

        $form = $this->createForm($this->get('welcomango.admin.form.experience.create'), $experience);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($experience);
            $em->flush();

            $this->addFlash('success', $this->trans('experience.created.success', array(), 'experience'));

            return $this->redirect($this->generateUrl('admin_experience_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/edit", name="admin_experience_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Experience $experience)
    {
        $form = $this->createForm($this->get('welcomango.admin.form.experience.edit'), $experience);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($experience);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('experience.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_experience_list'));
        }

        return array(
            'form'           => $form->createView(),
            'requested_experience' => $experience
        );
    }

    /**
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/delete", name="admin_experience_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Experience $experience)
    {
        $experience->setDeleted(true);
        $experience->setPublicationStatus('deleted');

        $this->getDoctrine()->getManager()->remove($experience);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_experience_list'));
    }

    /**
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/validate", name="admin_experience_validate")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAction(Experience $experience)
    {
        $experience->setPublicationStatus('published');
        $experience->setUpdatedStatus(true);
        $experience->setModeratedBy($this->getUser());

        $message = \Swift_Message::newInstance()
            ->setSubject('Your Experience has been validated')
            ->setFrom('no-reply@welcomango.com')
            ->setTo($experience->getCreator()->getEmail())
            ->setBody(
                $this->renderView(
                    'WelcomangoEmailBundle:EmailTemplate:experienceValidation.html.twig',
                    array(
                        'experience' => $experience,
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        $this->getDoctrine()->getManager()->persist($experience);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', $this->trans('experience.validated', array(), 'experience'));

        return $this->redirect($this->generateUrl('admin_moderation_experience'));
    }

    /**
     * @param Request    $request
     * @param Experience $experience
     *
     * @Route("/experience/{experience_id}/refuse", name="admin_experience_refuse")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseAction(Request $request, Experience $experience)
    {
        $form = $this->createForm($this->get('welcomango.form.refusal.form'), null);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $reason = $form->get('reason')->getData();
            $experience->setRefusedFor($reason);
            $experience->setRefusedAt(new \DateTime());
            $experience->setPublicationStatus('refused');
            $experience->setUpdatedStatus(true);
            $experience->setModeratedBy($this->getUser());

            $this->getDoctrine()->getManager()->persist($experience);
            $this->getDoctrine()->getManager()->flush();
        }

        $this->addFlash('success', $this->trans('experience.refused', array(), 'experience'));

        return $this->redirect($this->generateUrl('admin_moderation_experience'));
    }

    /**
     * @param Request $request
     *
     * @Route("/moderation/experience", name="admin_moderation_experience")
     * @Template()
     *
     * @return array
     */
    public function moderationListAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Experience')->findBy(['publicationStatus' => 'pending', 'deleted' => false]);
        $reasons = array('The pictures attached are not related to the experience and/or offensives', 'The description is too broad', 'The description contains personal informations', 'The description containes banned words, abreviations');
        $form = $this->createForm($this->get('welcomango.form.refusal.form'), null);

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            10
        );

        return array(
            'pagination' => $pagination,
            'reasons'    => $reasons,
            'form'       => $form->createView(),
        );
    }

    /**
     * Process and render form filters
     *
     * @param Request $request
     *
     * @Route("/experiences/filters/research", name="admin_experiences_filters")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterFormAction(Request $request)
    {
        if ($request->request->has('_reset')) {
            $this->removeFilters('admin_experienceSearch');

            return $this->redirect($this->generateUrl('admin_experience_list'));
        }

        $form = $this->createForm($this->get('welcomango.admin.form.experience.filter'), null);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $datas = $form->getData();

            $this->setFilters($datas, 'admin_experienceSearch');

        }

        return $this->redirect($this->generateUrl('admin_experience_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/experience/_experience_search_ajax", name="admin_experience_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $experiences = $this->getRepository('Welcomango\Model\Experience')->findByQuery($query);

        return array(
            'experience' => $experiences
        );
    }
}
