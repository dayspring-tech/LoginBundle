<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/15/16
 * Time: 2:42 PM
 */

namespace Dayspring\LoginBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('password', PasswordType::class, array(
            'attr' => array('class' => 'password-field', 'style' => 'max-width:300px'),
            'required' => true,
            'label' => "Enter Your Current Password"
        ));

        $builder->add('newPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'options' => array('attr' => array('class' => 'password-field', 'style' => 'max-width:300px')),
            'first_options' => array('label' => 'New Password'),
            'second_options' => array('label' => 'Repeat New Password'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'password'),
        ));
    }
}
