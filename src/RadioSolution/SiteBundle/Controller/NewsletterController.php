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
                //die(var_dump($data));

                $mc = $this->get('hype_mailchimp');
                try {
                    $format = 'html'; //receive newsletters in HTML
                    $doubleOptin = false; // do not send an email to confirm subscription
                    $res = $mc
                        ->getList()
                        ->subscribe($data['email'], $format, $doubleOptin)
                    ;

                    if (!empty($res['euid'])) {
                        // success message
                        $this->get('session')->getFlashBag()->add(
                            'newsletter_success',
                            'Votre adresse e-mail a bien été ajoutée'
                        );
                    } elseif(!empty($res['status']) && $res['status'] == 'error' && !empty($res['name'])) {
                        switch($res['name']) {
                            case 'Email_NotExists':
                                $message = 'Votre adresse e-mail est incorrecte ou n’existe pas';
                                break;
                            case 'List_AlreadySubscribed':
                                $message = 'Votre adresse e-mail est déjà présente dans la liste des abonnés';
                                break;
                            case 'List_DoesNotExist':
                                $message = 'Erreur, la liste d’abonnement n’existe pas';
                                break;
                            default:
                                $message = 'Erreur !' . $res['error'];

                        }
                        $this->get('session')->getFlashBag()->add(
                            'newsletter_error',
                            $message
                        );
                    } else {
                        $this->get('session')->getFlashBag()->add(
                            'newsletter_error',
                            'Une erreur est survenue lors de votre inscription (%s)<br>' . var_dump($res)
                        );
                    }

                } catch(\Exception $e) {
                    var_dump($e);
                }
            }

            $referer = $this->getRequest()->headers->get('referer') . '#newsletter-subscription';
            //die(var_dump($referer));
            return $this->redirect($referer);
        }

        return $this->render('SiteBundle:Newsletter:subscription.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
