<?php

namespace App\Form;

use App\Entity\Intrigue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntrigueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, ['attr' => ['placeholder' => "Le nom de l'intrigue"]])
        ->add('summary', null, ['attr' => ['placeholder' => "Le résumé de l'intrigue, qui apparaît sur la carte"]])
        ->add('type', null, ['attr' => ['placeholder' => "Ecrivez ici si l'intrigue représente une quête, une faction, ou simplement un élément de scénario"]])
        ->add('fluff', null, ['attr' => ['placeholder' => "La description diégétique de l'intrigue, ce qu'elle représente dans l'histoire, le monde. 
Par exemple : \"La Cinquième Aiguille est une organisation religieuse mystérieuse, qui prône l'existence qu'une cinquième bête cardinale et la vénère. Ont dit qu'ils sont profondément instauré dans les hautes instances du pays, même si le citoyen moyen n'en a jamais entendu parler.\""]])
        ->add('crunch', null, ['attr' => ['placeholder' => "La description du rôle du PNJ dans le jeu. Vous pouvez aussi y inclure un bloc statistique. 
Par exemple : \"La Cinquième Aiguille est une organisation antagoniste de haut niveau, qui envoie en premier lieu les joueurs éliminer des menaces avant qu'ils ne remarquent petit à petit les incohérences, et réalisent finalement qu'ils sont manipulés. Le leader de la Cinquième Aiguille est l'antagoniste principal de la campagne.\""]])
        ->add('imgFile', FileType::class, ['required' => false, 'data_class' => null, 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intrigue::class,
        ]);
    }
}
