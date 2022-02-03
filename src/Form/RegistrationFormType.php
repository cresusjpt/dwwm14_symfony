<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'mapped' => false,
                'constraints' => [
                    new Email([
                        'message' => 'Saisir une adresse mail valide'
                    ])
                ],
                'attr' => [
                    'class' => 'alert',
                    'placeholder' => 'Tapez votre adresse mail ici'
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex(
                        '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'Mot de passe incorrect: Huit caractères au moins, au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial'
                    )
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('surname', null, [
                'label' => 'Prénom',
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('city', null, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'rows' => 10,
                    'cols' => 30,
                ]
            ])
            ->add('pobox', NumberType::class, [
                'label' => 'Boite postale',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "Accepter nos conditions d'utilisation",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('Register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
