<?php

namespace RadioSolution\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;

class NewsletterController extends Controller
{
    public function subscribeAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('email', 'email', array(
                'label' => 'Adresse e-mail',
                'constraints' => new Email()
            ))
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                die(var_dump($data));

                $mc = $this->get('hype_mailchimp');
                $res = $mc
                    ->getList()
                    ->subscribe($data['email'])
                ;

                die(var_dump($res));
            }

            $referer = $this->getRequest()->headers->get('referer');
            die(var_dump($referer));
            return $this->redirect($referer);
        }

        return $this->render('SiteBundle:Newsletter:subscription.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
