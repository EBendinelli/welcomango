<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Model\User;
use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;

/**
 * Class AdminUserController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("user", options={"id" = "user_id"})
 */
class AdminUserController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/user/list", name="admin_user_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $filters   = $this->getFilters(array(), 'userSearch');
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\User')->createPagerQueryBuilder($filters);

        $form = $this->createForm($this->get('welcomango.form.user.filter'), $filters);

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50
        );

        return array(
            'pagination' => $pagination,
            'form'       => $form->createView(),
        );
    }

    /**
     * @param Request $request
     *
     * @Route("/user/create", name="admin_user_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $user = $this->get('fos_user.user_manager')->createUser();
        $form = $this->createForm($this->get('welcomango.form.user.type'), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $this->get('fos_user.user_manager')->updateUser($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('user.created.success', array(), 'user'));

            return $this->redirect($this->generateUrl('admin_user_list'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/user/{user_id}/edit", name="admin_user_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm($this->get('welcomango.form.user.type'), $user);

        $originalSpokenLanguages = new ArrayCollection();
        foreach ($user->getSpokenLanguages() as $spokenLanguage) {
            $originalSpokenLanguages->add($spokenLanguage);
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            foreach ($originalSpokenLanguages as $originalSpokenLanguage) {
                if (false === $form->getData()->getSpokenLanguages()->contains($originalSpokenLanguage)) {
                    $this->getDoctrine()->getManager()->remove($originalSpokenLanguage);
                }
            }

            if ($form->get('media_profile')->getData()) {
                $media = $this->get('welcomango.media.manager')->generateSimpleMedia($form->get('media_profile')->getData()->getClientOriginalName(), $user);
                $user->setProfileMedia($media);
            }

            $this->get('fos_user.user_manager')->updateUser($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('user.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_user_list', array(
                'user_id'        => $user->getId(),
                'requested_user' => $user,
            )));
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @param User $user
     *
     * @Route("/user/{user_id}/delete", name="admin_user_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(User $user)
    {
        $user->setEnabled(false);
        $user->setDeleted(true);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_user_list'));
    }

    /**
     * @param User $user
     *
     * @Route("/user/{user_id}/validate", name="admin_user_validate")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateAction(User $user)
    {
        $user->setEnabled(true);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_user_list'));
    }

    /**
     * List cities for Ajax Calls
     *
     * @param Request $request
     *
     * @Route("/json/cities/list.json", name="admin_user_search_ajax", defaults={"_format"="json"})
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
     * @Route("/users/filters/research", name="admin_users_filters")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterFormAction(Request $request)
    {
        if ($request->request->has('_reset')) {
            $this->removeFilters('userSearch');

            return $this->redirect($this->generateUrl('admin_user_list'));
        }

        $form = $this->createForm($this->get('welcomango.form.user.filter'), null);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $datas = $form->getData();
            $this->setFilters($datas, 'userSearch');
        }

        return $this->redirect($this->generateUrl('admin_user_list'));
    }
}
