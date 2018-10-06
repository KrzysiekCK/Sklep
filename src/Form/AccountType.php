<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, array(
                'label' => 'Login'
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Hasło'),
                'second_options' => array('label' => 'Powtórz Hasło'),
            ))
            ->add('name', TextType::class, array(
               'label' => 'Imię'
            ))
            ->add('surname', TextType::class, array(
                'label' => 'Nazwisko'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email'
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Telefon Kontaktowy'
            ))
            ->add('address', TextType::class, array(
                'label' => 'Ulica i Numer'
            ))
            ->add('zip', TextType::class, array(
                'label' => 'Kod Pocztowy'
            ))
            ->add('city', TextType::class, array(
                'label' => 'Miasto'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Account::class,
        ));
    }
}
