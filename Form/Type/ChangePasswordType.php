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

        $builder->add('password', PasswordType::class, ['attr' => ['class' => 'password-field', 'style' => 'max-width:300px'], 'required' => true, 'label' => "Enter Your Current Password"]);

        $builder->add('newPassword', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'The password fields must match.', 'required' => true, 'options' => ['attr' => ['class' => 'password-field', 'style' => 'max-width:300px']], 'first_options' => ['label' => 'New Password'], 'second_options' => ['label' => 'Repeat New Password']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['validation_groups' => ['Default', 'password']]);
    }
}
