<?php

namespace App\Form;

use App\Entity\Encounter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EncounterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, ['attr' => ['placeholder' => "Le nom de la rencontre"]])
        ->add('summary', null, ['attr' => ['placeholder' => "Le résumé de la rencontre, qui apparaît sur la carte"]])
        ->add('fluff', null, ['attr' => ['placeholder' => "La description diégétique de la rencontre, ce qu'elle représente dans l'histoire, le monde. 
Par exemple : \"Les fier à bras sont une compagnie d'aventuriers un peu simplet et arrogants, qui sont persuadés d'être les prochains héros du royaume. Ils n'hésitent pas à raquetter ou attaquer les autres voyageurs pour se remplir les poches.\""]])
        ->add('crunch', null, ['attr' => ['placeholder' => "La description du rôle du PNJ dans le jeu. Vous pouvez aussi y inclure un bloc statistique. 
Par exemple : \"Les fier à bras sont un groupe de 3 à 5 aventuriers, ayant la même composition que les joueurs. Ils débutent au 5ème niveau, mais gagnent moins d'expérience que les joueurs. Ils sont stupide et peuvent facilement être bernés, mais ils sont redoutable en combat direct si les joueurs sont bas niveaux.\""]])
            ->add('rewards', null, ['attr' => ['placeholder' => "Ce que les joueurs gagnent à surmonter cette rencontre. 
Par exemple : \"Les fier à bras possèdent 5d20 pièces d'or, et plusieurs artefacts magiques de leur niveau. Les joueurs peuvent découvrir qu'un membre de leur groupe est victime de bizutage (surement le mage), et peuvent le recruter si ils résolvent la situation sans trop de violence.\""]])
            ->add('img', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Encounter::class,
        ]);
    }
}
