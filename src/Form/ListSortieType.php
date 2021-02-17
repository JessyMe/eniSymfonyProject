<?php

namespace App\Form;

use App\Entity\Campus;
use App\Service\ListFormSortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, ['class'=>Campus::class,'choice_label'=>'nom_campus','label'=>"Campus",'required'=>false])
            ->add('nom',SearchType::class,['label'=>"Le nom de la sortie contient",'required'=>false])
            ->add('datedebut', DateTimeType::class,['label'=>"Entre",'required'=>false])
            ->add('datefin', DateTimeType::class,['label'=>"Entre",'required'=>false])
            ->add('SortieOrganisateur', CheckboxType::class,['required'=>false])
            ->add('SortieInscrit', CheckboxType::class,['label'=>"Sorties auxquelles je suis inscrit/e",'required'=>false])
            ->add('SortieNonInscrit', CheckboxType::class,['label'=>"Sorties auxquelles je ne suis pas inscrit/e",'required'=>false])
            ->add('SortiePassee', CheckboxType::class,['label'=>"Sorties passées",'required'=>false])

//'label'=>"Sorties dont je suis l'organisateur/trice",
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListFormSortie::class
        ]);
    }
}
