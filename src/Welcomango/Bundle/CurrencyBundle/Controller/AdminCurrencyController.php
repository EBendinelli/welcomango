<?php

namespace Welcomango\Bundle\CurrencyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Welcomango\Bundle\CoreBundle\Controller\Controller as BaseController;
use Welcomango\Model\Currency;

/**
 * Class AdminCurrencyController
 *
 * @Route("/welcomadmin")
 * @ParamConverter("currency", options={"id" = "currency_id"})
 */
class AdminCurrencyController extends BaseController
{
    /**
     * @param Request $request
     *
     * @Route("/currency/list", name="admin_currency_list")
     * @Template()
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $query     = $this->getRepository('Welcomango\Model\Currency')->findAll();
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
     * @Route("/currency/create", name="admin_currency_create")
     * @Template()
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $currency = new Currency();
        $currency->setCreatedAt(new \DateTime());
        $currency->setUpdatedAt(new \DateTime());
        $form = $this->createForm($this->get('welcomango.form.currency.create'), $currency);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currency);
            $em->flush();

            $this->addFlash('success', $this->trans('currency.created.success', array(), 'currency'));

            return $this->redirect($this->generateUrl('admin_currency_list'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request    $request
     * @param Currency $currency
     *
     * @Route("/currency/{currency_id}/edit", name="admin_currency_edit")
     * @Template()
     *
     * @return array
     */
    public function editAction(Request $request, Currency $currency)
    {
        $form = $this->createForm($this->get('welcomango.form.currency.create'), $currency);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $currency->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($currency);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->trans('currency.edit.success', array(), 'crm'));

            return $this->redirect($this->generateUrl('admin_currency_list'));
        }

        return array(
            'form'           => $form->createView(),
            'currency' => $currency
        );
    }

    /**
     * @param Currency $currency
     *
     * @Route("/currency/{currency_id}/delete", name="admin_currency_delete")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Currency $currency)
    {

        $this->getDoctrine()->getManager()->remove($currency);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('admin_currency_list'));
    }

    /**
     * @Route("/currency/update_rates", name="admin_currency_update_rates")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateRateAction(){
        $currencyManager = $this->get('welcomango.currency.manager');
        $currencyManager->updateCurrencyRates();

        return $this->redirect($this->generateUrl('admin_currency_list'));
    }

    /**
     * @param Request $request
     *
     * @Route("/currency/_currency_search_ajax", name="admin_currency_search_ajax")
     * @Method("POST")
     *
     * @return array
     */
    public function ajaxSearchAction(Request $request)
    {
        $query       = $request->request->get('query');
        $currency = $this->getRepository('Welcomango\Model\Currency')->findByQuery($query);

        return array(
            'currency' => $currency
        );
    }
}
