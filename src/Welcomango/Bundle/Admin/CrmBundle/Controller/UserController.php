<?php

namespace Welcomango\Bundle\Admin\CrmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\Admin\CoreBundle\Controller\Controller;
use Welcomango\Model\User;

/**
 * Class UserController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("user", options={"id" = "user_id"})
 */
class UserController extends Controller
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
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\User')->findAll();

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            50
        );

        return array(
            'pagination' => $pagination,
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

            foreach($user->getSpokenLanguages() as $spokenLanguage) {
                $spokenLanguage->setUser($user);
                $this->getDoctrine()->getManager()->persist($spokenLanguage);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $this->trans('user.created.success', array(), 'user'));

            return $this->redirect($this->generateUrl('admin_user_list'));
        }

        return array(
            'form' => $form->createView()
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
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->get('fos_user.user_manager')->updateUser($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('user.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_user_list', array(
                'user_id'        => $user->getId(),
                'requested_user' => $user,
            )));
        }

        return array(
            'form'           => $form->createView(),
            'user' => $user
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
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_user_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/user/_user_search_ajax", name="admin_user_search_ajax")
     * @Method("POST")
     * @Template("YproxAdminCrmBundle:User:_users.html.twig")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query = $request->request->get('query');
        $users = $this->getRepository('Yprox\Model\User')->findByQuery($query);

        return array(
            'users' => $users
        );
    }
}
