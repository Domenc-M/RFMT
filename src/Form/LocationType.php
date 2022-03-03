<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, ['attr' => ['placeholder' => "Le nom du lieu"]])
        ->add('summary', null, ['attr' => ['placeholder' => "Le résumé du lieu, qui apparaît sur la carte"]])
        ->add('fluff', null, ['attr' => ['placeholder' => "La description diégétique du lieu, ce qu'il représente dans l'histoire, le monde. 
Par exemple : \"Arghuta est un ancien village de pêcheur, qui depuis l'infestation de l'océan doit survivre face à de monstrueuses créatures\""]])
        ->add('crunch', null, ['attr' => ['placeholder' => "La description du rôle du lieu dans le jeu. Vous pouvez aussi y inclure un bloc statistique. 
Par exemple : \"Arghuta est un village proche de la source de l'infestation, les joueurs pourrons se ravitailler et obtenir des informations avant d'attaquer le donjon sous-marin. \""]])
        ->add('imgFile', FileType::class, ['required' => false, 'data_class' => null, 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
