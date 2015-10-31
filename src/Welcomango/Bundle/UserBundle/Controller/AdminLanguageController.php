<?php

namespace Welcomango\Bundle\UserBundle\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Model\User;
use Welcomango\Model\Language;
use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;

/**
 * Class AdminLanguageController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("language", options={"id" = "language_id"})
 */
class AdminLanguageController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/language/list", name="admin_language_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Language')->findAll();

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
     * @Route("/language/create", name="admin_language_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $language = new Language();

        $form = $this->createForm($this->get('welcomango.form.language.type'), $language);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $language = $form->getData();

            $this->getDoctrine()->getManager()->persist($language);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $this->trans('language.created.success', array(), 'language'));

            return $this->redirect($this->generateUrl('admin_language_list'));
        }


        return array(
            'form'        => $form->createView(),
            'flagsImages' => $this->getFlagNames(),
        );
    }

    /**
     * @param Request  $request
     * @param Language $language
     *
     * @Route("/language/{language_id}/edit", name="admin_language_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Language $language)
    {
        $form = $this->createForm($this->get('welcomango.form.language.type'), $language);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($language);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('language.edit.success', array(), 'language'));

            return $this->redirect($this->generateUrl('admin_language_list'));
        }

        return array(
            'form'        => $form->createView(),
            'language'    => $language,
            'flagsImages' => $this->getFlagNames(),
        );
    }

    /**
     * @param Language $language
     *
     * @Route("/language/{language_id}/delete", name="admin_language_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Language $language)
    {
        $this->getDoctrine()->getManager()->remove($language);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_language_list'));
    }

    /**
     * return flag names without extension
     *
     * @return array
     */
    protected function getFlagNames()
    {
        $finder = new Finder();
        $flags  = array();

        $finder->files()->in('img/countries');

        foreach ($finder->files() as $file) {
            $flagName = explode(".", $file->getFileName());
            $flags[]  = $flagName[0];
        }

        return $flags;
    }
}
