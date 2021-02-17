<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\False_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=> 'Nom de la sortie :'
            ])
            ->add('datedebut', DateTimeType::class,[
                'label'=> 'Date et heure de la sortie :',
                'widget'=>'choice',
            ])
            ->add('datecloture', DateType::class,[
                'label'=> 'Date limite d\'inscription :',
                'widget'=>'choice',
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label'=>'Nombre de places :',
                'attr' => ['class' =>'form-control',
                    'placeholder' => '20']
            ])
            ->add('duree', IntegerType::class, [
                'attr' => ['class' =>'form-control',
                    'placeholder' => '90']
            ])

            ->add('descriptionInfos', TextareaType::class, [
                'label'=>'Description et infos :',
                'attr' => ['class' =>'form-control']
            ]);

        $builder->add('lieu', CollectionType::class, [
            'entry_type' => LieuType::class,
            'entry_options' => ['label' => false],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
