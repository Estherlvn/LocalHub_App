<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\File;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventTitle', TextType::class, [
                'label' => 'Intitulé de l\'événement',
                'attr' => ['placeholder' => 'Entrez un titre pour l\'événement'],
            ])
            ->add('eventDate', DateType::class, [
                'label' => 'Date de l\'événement',
                'widget' => 'single_text',
                'attr' => ['placeholder' => 'Sélectionnez une date'],
            ])
            ->add('eventLocation', TextType::class, [
                'label' => 'Lieu de l\'événement',
                'attr' => ['placeholder' => 'Entrez un lieu pour l\'événement'],
            ])
            ->add('eventDescription', TextareaType::class, [
                'label' => 'Description de l\'événement',
                'required' => false,
                'attr' => ['placeholder' => 'Ajoutez une description...'],
            ])
            ->add('eventPicture', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image JPG ou PNG valide.',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
