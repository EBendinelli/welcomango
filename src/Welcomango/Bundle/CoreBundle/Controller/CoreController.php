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
     * @Route("/currency", name="front_currency_select")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function currencyAction(Request $request)
    {
        $currencyCode = $request->get('currency');
        $page = $request->get('page');

        $session = $this->get('session');
        $currencyRepo = $this->getRepository('Welcomango\Model\Currency');
        $currency = $currencyRepo->findOneBy(['code' => $currencyCode]);

        $session->set('currency', $currency );

        return $this->redirect($page);

        die();
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
        $feedbackUrl = $request->get('feedback_url');
        $category = $request->get('category');

        $user = $this->getUser();
        $form = $this->createForm(new ContactType($user, $category));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $sendTo = "contact@welcomango.com";
            if($form->get('category')->getData() == "Bug"){
                $sendTo = "dev@welcomango.com";
            }

            //transmit the message to us
            $message = \Swift_Message::newInstance()
                ->setSubject('New '.$form->get('category')->getData().' from '.$form->get('name')->getData())
                ->setFrom($form->get('email')->getData())
                ->setTo($sendTo)
                ->setBody(
                    $this->renderView(
                        'WelcomangoEmailBundle:AdminEmailTemplate:contact.html.twig',[
                        'user' => $user,
                        'category' => $form->get('category')->getData(),
                        'name' => $form->get('name')->getData(),
                        'message' => $form->get('message')->getData(),
                        'issue_url' => $feedbackUrl,
                        'type' => 'Text',
                        ]),
                    'text/plain'
                )
                ->addPart(
                    $this->renderView(
                        'WelcomangoEmailBundle:AdminEmailTemplate:contact.html.twig',[
                        'user' => $user,
                        'category' => $form->get('category')->getData(),
                        'name' => $form->get('name')->getData(),
                        'message' => $form->get('message')->getData(),
                        'issue_url' => $feedbackUrl,
                        'type' => 'Text',
                    ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            //send a confirmation message to the user
            $message = \Swift_Message::newInstance()
                ->setSubject($this->trans('email.message.subject', array(), 'interface'))
                ->setFrom('no-reply@welcomango.com')
                ->setTo($form->get('email')->getData())
                ->setBody(
                    $this->renderView(
                        'WelcomangoEmailBundle:EmailTemplate:contactConfirmation.html.twig',[
                        'name' => $form->get('name')->getData(),
                        'type' => 'Text',
                    ]),
                    'text/plain'
                )
                ->addPart(
                    $this->renderView(
                        'WelcomangoEmailBundle:EmailTemplate:contactConfirmation.html.twig',[
                        'name' => $form->get('name')->getData(),
                    ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);


            return $this->render('WelcomangoCoreBundle:Core:success.html.twig', array(
                'title'           => $this->trans('contact.sent.title', array(), 'interface'),
                'sub_title'       => $this->trans('contact.sent.subTitle', array(), 'interface'),
                'message'         => $this->trans('contact.sent.message', array(), 'interface'),
                'button1_path'    => $this->get('router')->generate('front_homepage'),
                'button1_message' => $this->trans('global.backHome', array(), 'interface'),
            ));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
