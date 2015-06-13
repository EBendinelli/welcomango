<?php

namespace Welcomango\Bundle\CrmBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller;
use Welcomango\Model\User;

/**
 *
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/user/list", name="user_list")
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
     * Process and render form filters
     *
     * @param Request $request
     *
     * @Route("/users/filters/research", name="users_filters")s
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterFormAction(Request $request)
    {
        if ($request->request->has('_reset')) {
            $this->removeFilters('userSearch');

            return $this->redirect($this->generateUrl('user_list'));
        }

        $form = $this->createForm($this->get('yprox.form.user.filter'), null);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $datas = $form->getData();
            $this->setFilters($datas, 'userSearch');
        }

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/user/create", name="user_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $user = $this->get('fos_user.user_manager')->createUser();
        $form = $this->createForm($this->get('yprox.form.user.create'), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();

            $this->get('fos_user.user_manager')->updateUser($user);

            $this->addFlash('success', $this->trans('user.created.success', array(), 'user'));

            return $this->redirect($this->generateUrl('user_edit', array(
                'user_id' => $user->getId(),
            )));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/user/{user_id}/edit", name="user_edit")
     * @ParamConverter("user", options={"id" = "user_id"})
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm($this->get('yprox.form.user.edit'), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->get('fos_user.user_manager')->updateUser($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('user.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('user_edit', array(
                'user_id'        => $user->getId(),
                'requested_user' => $user,
            )));
        }

        return array(
            'form'           => $form->createView(),
            'requested_user' => $user
        );


    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @Route("/user/{user_id}/changePassword", name="user_change_password")
     * @ParamConverter("user", options={"id" = "user_id"})
     * @Template()
     *
     * @return array
     */
    public function changePasswordAction(Request $request, User $user)
    {

        $form = $this->createForm($this->get('yprox.form.user.change_password'), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $this->get('fos_user.user_manager')->updateUser($user);

            $this->addFlash('success', $this->trans('user.password.updated.success', array(), 'user'));

            return $this->redirect($this->generateUrl('user_edit', array(
                'user_id' => $user->getId()
            )));
        }

        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     *
     * @Route("/user/{user_id}/delete", name="user_delete")
     * @ParamConverter("user", options={"id" = "user_id"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(User $user)
    {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/user/_user_search_ajax", name="user_search_ajax")
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

    /**
     * @Template()
     *
     * @return array
     */
    public function usersByLastLoginAction()
    {
        $users = $this->getRepository('Yprox\Model\User')->findByLastLoggedIn(25);

        return array(
            'users' => $users
        );
    }

    public function usersByGroupAction()
    {
        $groups = $this->getRepository('Ylly\CrmBundle\Entity\Group')->findJoinUsers();

        return $this->render('YproxAdminCrmBundle:User:_usersByGroup.html.twig', array('groups' => $groups));
    }
}
