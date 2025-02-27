<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Track;
use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TrackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('trackName', TextType::class, [
            'label' => 'Titre du morceau',
            'attr' => ['placeholder' => 'Entrez le titre du morceau'],
        ])
        ->add('duration', IntegerType::class, [
            'label' => 'Durée (en secondes)',
            'attr' => ['placeholder' => 'Ex: 180 pour 3 minutes'],
        ])
         ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'genreName',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'checkbox-group']
        ])
        ->add('audioFile', FileType::class, [
            'label' => 'Fichier audio',
            'required' => false, // Rendre facultatif si besoin
            'attr' => ['accept' => 'audio/*'], // Autorise uniquement les fichiers audio

        ]);
    }
    
            // ->add('uploadDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
           
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
