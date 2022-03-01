<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, ['attr' => ['placeholder' => "Le nom de l'objet"]])
        ->add('summary', null, ['attr' => ['placeholder' => "Le résumé de l'objet, qui apparaît sur la carte"]])
        ->add('fluff', null, ['attr' => ['placeholder' => "La description diégétique de l'objet, ce qu'il représente dans l'histoire, le monde. 
Par exemple : \"Dainslef est une épée maudite. Pendant des générations les épéistes se la sont arrachés avant de mourrir horriblement. Aujourd'hui, l'épée a été enfermée au fond d'une forteresse peuplé de chevaliers qui ont juré de ne laisser personne déchaîner Dainslef de nouveau.\""]])
        ->add('crunch', null, ['attr' => ['placeholder' => "La description du rôle du PNJ dans le jeu. Vous pouvez aussi y inclure un bloc statistique. 
Par exemple : \"Dainslef est une épée à deux mains qui inflige 1d12 de dégât, tant que le joueur n'as pas infligé 12 points de dégât, il ne peut pas la rengainer. Chaque tour où elle est degainé, il doit attaquer au moins une personne, sans cible à portée, il doit s'attaquer lui même. Un immortel tué par Dainslef ne ressucite pas. \""]])
            ->add('img', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
