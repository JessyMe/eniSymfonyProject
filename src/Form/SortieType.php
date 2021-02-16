<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=>'Nom de la sortie :',
                'attr' => ['class' =>'form-control']
            ])
            ->add('datedebut', DateType::class,[
                'label'=> 'Date et heure de la sortie :',
                'widget'=>'choice',
            ])
            ->add('duree', IntegerType::class, [
                'attr' => ['class' =>'form-control']
            ])
            ->add('datecloture', DateType::class,[
                'label'=> 'Date limite d\'inscription :',
                'widget'=>'choice',
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label'=>'Nombre maximum de participants :',
                'attr' => ['class' =>'form-control']
            ])
            ->add('descriptionInfos', TextType::class, [
                'label'=>'Description et infos :',
                'attr' => ['class' =>'form-control']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
