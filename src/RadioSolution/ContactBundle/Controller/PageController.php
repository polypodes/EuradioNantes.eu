<?php

namespace RadioSolution\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RadioSolution\ContactBundle\Form\EnquiryType;
use RadioSolution\ContactBundle\Entity\Enquiry;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem('Contact');

        $enquiry = new Enquiry();

        $contacts = $em
            ->createQuery('SELECT c FROM ContactBundle:Contact c ORDER BY c.position ASC')
            ->getResult()
        ;

        $form = $this->createForm(new EnquiryType($contacts), $enquiry);

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle("Contact - Euradionantes");

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $fromAddress = $data->getEmail();
            $toAddress = !empty($data->getRecipient()) ? $data->getRecipient() : $this->container->getParameter('contact_address');
            $message = \Swift_Message::newInstance()
                ->setSubject('[Euradionantes] Demande de contact')
                ->setFrom($fromAddress)
                ->setTo($toAddress)
                ->setCc($fromAddress)
                ->setBody($this->renderView('ContactBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)))
            ;
            if ($this->get('mailer')->send($message)) {
                $this->get('session')->getFlashBag()->add('contact', 'Votre message a bien été envoyé.');
                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('contact'));
            } else {
                $this->get('session')->getFlashBag()->add('contact', 'Une erreur s’est produite lors de l’envoi de votre message.');
            }
        }



        // @todo Change static ID by a more clever solution
        $entity = $em->getRepository('StaticContentBundle:StaticContent')->findOneById(30);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StaticContent entity.');
        }

        return $this->render('ContactBundle:Page:contact.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'contacts' => $contacts
        ));
    }
}
