<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/15/16
 * Time: 2:33 PM
 */

namespace Dayspring\LoginBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'The password fields must match.', 'required' => true, 'options' => ['attr' => ['class' => 'password-field']], 'first_options' => ['label' => 'Password'], 'second_options' => ['label' => 'Repeat Password']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['validation_groups' => ['Default', 'password']]);
    }
}
