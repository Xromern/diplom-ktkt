<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {//input100
        $builder
            ->add('email', EmailType::class,
                array('label' => ' ',
                'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'focus-input100','placeholder'=>'Email'),
                'attr'       => array('class' => 'input100','placeholder'=>'Email'),
            ))
            ->add('username', null, array('label' => ' ',
                'translation_domain' => 'FOSUserBundle',
                'label_attr' => array('class' => 'focus-input100','placeholder'=>'Логін'),
                'attr'       => array('class' => 'input100','placeholder'=>'Логін'),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => ' ',
                'label_attr' => array('class' => 'focus-input100','placeholder'=>'Пароль'),
                'attr'       => array('class' => 'input100','placeholder'=>'Пароль'),
                ),
                'second_options' => array('label' => ' ',
                'label_attr' => array('class' => 'focus-input100'),
                'attr'       => array('class' => 'input100','placeholder'=>'Пітвердіть пароль'),
                ),
                'invalid_message' => 'password.mismatch',
            ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}