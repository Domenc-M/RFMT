<?php

namespace App\Form;

use App\Entity\Npc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NpcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['attr' => ['placeholder' => "Le nom du PNJ"]])
            ->add('summary', null, ['attr' => ['placeholder' => "Le résumé du PNJ, qui apparaît sur la carte"]])
            ->add('fluff', null, ['attr' => ['placeholder' => "La description diégétique du PNJ, qui il est dans l'histoire, le monde. 
Par exemple : \"Arthur Lançoit est un chevalier du royaume de Mordon, qui cherche à prouver que le roi est un imposteur.\""]])
            ->add('crunch', null, ['attr' => ['placeholder' => "La description du rôle du PNJ dans le jeu. Vous pouvez aussi y inclure un bloc statistique. 
Par exemple : \"Lennure est un alchimiste qui peut vendre des produits interdits aux joueurs. Il possède 2d8 PV, et peut utiliser une bombe incendiaire (2d8 dégât de feu) si il est menacé. \""]])
            ->add('img', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Npc::class,
        ]);
    }
}
