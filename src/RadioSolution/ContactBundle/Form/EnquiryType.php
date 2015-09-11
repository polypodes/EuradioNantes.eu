<?php
namespace RadioSolution\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\Form\FormView;
//use Symfony\Component\Form\FormInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class EnquiryType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name');
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
