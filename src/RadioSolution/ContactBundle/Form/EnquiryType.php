<?php
namespace RadioSolution\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\Form\FormView;
//use Symfony\Component\Form\FormInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class EnquiryType extends AbstractType
{
	private $contacts = array();

	public function __construct(array $contacts)
	{
		foreach ($contacts as $contact) {
	    	$this->contacts[$contact->getEmail()] = $contact->getTitle();
	    }
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('recipient', 'choice', array('choices' => $this->contacts));
		$builder->add('name', 'text', array());
		$builder->add('company');
		$builder->add('email', 'email');
		$builder->add('subject');
		$builder->add('body', 'textarea');
		$builder->add('recaptcha', 'ewz_recaptcha');
	}

	public function getName()
	{
		return 'contact';
	}
}
