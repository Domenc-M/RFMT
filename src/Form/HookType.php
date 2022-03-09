<?php

namespace App\Form;

use App\Entity\Hook;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['attr' => ['placeholder' => "Le nom de l'amorce"]])
            ->add('summary', null, ['attr' => ['placeholder' => "Le résumé de l'amorce, qui apparaît sur la carte"]])
            ->add('description', CKEditorType::class, ['attr' => ['placeholder' => "La description de l'amorce, une petite histoire qui donne un début d'aventure. 
Par exemple : 
\"Les joueurs arrivent dans une petite ville où la population est très accueillante, mais les habitants refusent qu'ils passent la nuit ici. Lorsque les joueurs sont forcés de camper dehors, ils entendent des hurlement de loups provenir de l'intérieur de la ville...\""]])
            ->add('subHook', CKEditorType::class, ['attr' => ['placeholder' => "Les intrigues supplémentaires possible, elles peuvent être contradictoire. 
Par exemple : 
\"-Une sorcière locale a maudit la ville, les forçant à se transformer en loup-garou chaque nuits, mais les habitants sont trop fier pour demander de l'aide.
-Un des habitants est un puissant loup-garou, et la ville entière tente de le piéger chaque nuit, sans succès.
-Les habitants sont en réalité des loups qui adoptent une apparence humaine le jour. Ils ne sont pas mauvais, mais ont peur de la réaction des humains locaux.\""]])
            ->add('imgFile', FileType::class, ['required' => false, 'data_class' => null, 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hook::class,
        ]);
    }
}
