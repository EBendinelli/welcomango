<?php

namespace Welcomango\Bundle\CoreBundle\Controller;

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
use Welcomango\Bundle\CoreBundle\Form\Type\ContactType;

class CoreController extends BaseController
{
    /**
     * @Route("/", name="front_homepage")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction()
    {
        // Loading Featured Experiences
        $featuredExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->getFeatured(3);

        $bestExperiences = $this
            ->getRepository('Welcomango\Model\Experience')
            ->getBestRated(4);

        $filters = $this->getFilters(array(), 'experienceSearch');
        $entityManager = $this->getDoctrine()->getManager();
        if (isset($filters['tags'])) {
            foreach ($filters['tags'] as $tag) {
                $entityManager->persist($tag);
            }
        }
        $form = $this->createForm($this->get('welcomango.form.experience.filter'), $filters);

        return array(
            'featuredExperiences' => $featuredExperiences,
            'bestExperiences'     => $bestExperiences,
            'form'                => $form->createView(),
        );
    }

    /**
     * @param Request    $request
     *
     * @Route("/contact", name="front_contact_us")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function contactAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new ContactType($user));
        $form->handleRequest($request);

        if ($form->isValid()) {
            //transmit the message to us
            $message = \Swift_Message::newInstance()
                ->setSubject('New '.$form->get('category')->getData().' from '.$form->get('name')->getData())
                ->setFrom($form->get('email')->getData())
                ->setTo('contact@welcomango.com')
                ->setBody(
                    $this->renderView(
                        'WelcomangoEmailBundle:EmailTemplate:contact.html.twig',[
                            'user' => $user,
                        'ip' => $request->getClientIp(),
                        'name' => $form->get('name')->getData(),
                        'message' => $form->get('message')->getData(),
                        ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            //send a confirmation message to the user
            $message = \Swift_Message::newInstance()
                ->setSubject('Your message has been successfully sent!')
                ->setFrom('contact@welcomango.com')
                ->setTo($form->get('email')->getData())
                ->setBody(
                    $this->renderView(
                        'WelcomangoEmailBundle:EmailTemplate:contactConfirmation.html.twig',[
                        'name' => $form->get('name')->getData(),
                    ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            return $this->redirect($this->generateUrl('front_validation'));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
