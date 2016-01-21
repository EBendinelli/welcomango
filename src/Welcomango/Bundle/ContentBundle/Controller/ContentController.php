<?php

namespace Welcomango\Bundle\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Page;

/**
 * Class ContentController
 *
 * @ParamConverter("page", options={"id" = "page_id", "slug" = "page_slug"})
 */
class ContentController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/portraits", name="page_portrait_list")
     * @Template()
     *
     * @return array
     */
    public function portraitListAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $portraitCat = $this->getRepository('Welcomango\Model\Category')->findOneBy(['name' => 'Portrait']);
        $query     = $this->getRepository('Welcomango\Model\Page')->findBy(['category' => $portraitCat]);
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
     * @param Page $page
     *
     * @Route("/read/{slug}", name="page_view_slug")
     * @ParamConverter("page", options={"slug" = "slug"})
     * @Template()
     *
     * @return array
     */
    public function viewSlugAction(Page $page)
    {

        if($page->getPublicationStatus() != 'published'){
            return $this->render('WelcomangoCoreBundle:CRUD:notAllowed.html.twig', array(
                'title'          => 'This article has not been published yet.',
                'message'        => 'Just wait a moment until we validate it',
                'return_path'    => $this->get('router')->generate('front_homepage'),
                'return_message' => 'Back to home',
                'icon'           => 'fa-clock-o',
            ));
        }

        $coreCategories[] = $this->getRepository('Welcomango\Model\Category')->findOneBy(['name' => 'How To' ]);
        $coreCategories[] = $this->getRepository('Welcomango\Model\Category')->findOneBy(['name' => 'About' ]);

        $pageCategory = $page->getCategory();
        if(in_array($pageCategory, $coreCategories)) {
            return $this->render('WelcomangoContentBundle:Content:viewPage.html.twig', array(
                'page' => $page,
                'categories' => $coreCategories
            ));
        }elseif($pageCategory == $this->getRepository('Welcomango\Model\Category')->findOneBy(['name' => 'Portrait' ])){
            return $this->render('WelcomangoContentBundle:Content:viewPortrait.html.twig', array(
                'page' => $page
            ));
        }else{
            return $this->render('WelcomangoContentBundle:Content:viewArticle.html.twig', array(
                'page' => $page
            ));
        }
    }

    /**
     * @param Page $page
     *
     * @Route("/page/{page_id}/delete", name="page_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Page $page)
    {
        $page->setDeleted(true);
        $page->setPublicationStatus('deleted');
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_page_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/page/_page_search_ajax", name="page_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $page = $this->getRepository('Welcomango\Model\Page')->findByQuery($query);

        return array(
            'page' => $page
        );
    }
}
