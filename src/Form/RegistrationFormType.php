<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('pseudo')
            ->add('departement', TextType::class, [
                'label' => 'Département (01-95)',
                'attr' => [
                    'maxlength' => 2, // Empêche plus de 2 caractères
                    'pattern' => '[0-9]{2}', // Bloque les saisies non numériques
                    'placeholder' => 'Ex: 75',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le département est obligatoire.']),
                    new Regex([
                        'pattern' => '/^(0[1-9]|[1-8][0-9]|9[0-5])$/',
                        'message' => 'Veuillez entrer un département valide entre 01 et 95.'
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions d\'utilisation.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false, // ce champ n'est pas lié directement à l'entité
                'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                'first_options'  => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Saisissez votre mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Confirmez le mot de passe',
                ],
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Auditeur' => 'auditeur',
                    'Artiste'  => 'artiste',
                    // 'Admin'    => 'admin',
                ],
                'expanded' => false, // dropdown
                'multiple' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(auditeur|artiste|admin)$/',
                        'message' => 'Le rôle doit être auditeur, artiste ou admin.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
         $resolver->setDefaults([
             'data_class' => User::class,
         ]);
    }
}
