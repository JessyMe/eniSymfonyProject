<?php

namespace App\Form;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

            ])
            ->add('ville', EntityType::class,
            [
                'class'=>Ville::class,
                'choice_label'=>function($ville) {
                return $ville->getNomVille($ville);
                },
                'mapped'=> false,
                'placeholder' => 'choisir la ville',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('lieu', LieuType::class)
            ->add('save', SubmitType::class, [
                'label'=> 'Enregistrer'
            ])
            ->add('saveAndAdd', SubmitType::class, [
            'label'=> 'Publier',
             ])
            //->add('Annuler', ResetType::class);

            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'label'=>'Lieu',
                'choice_label'=>function($lieu){
                return $lieu->getNomLieu();
                },
                'placeholder'=>'Choisir un lieu'
            ]);

            /*->add('etat', EntityType::class, [
                'choice_label'=> 'libelle',
                'placeholder' => 'choisir etat',
                'class'=>Etat::class
            ])
            ->add('campus', EntityType::class,[
                'choice_label' => 'nomCampus', 'placeholder'=> '{{sortie.campus}}',
                    'class'=>Campus::class
                ]
            );


            /*->add('lieu', EntityType::class,[
                'choice_label'=> 'nomLieu', 'placeholder'=>'choisir un lieu',
                'class'=>Lieu::class,
                'choice_label'=> 'ville', 'placeholder'=>'choisir une ville',
                'class'=>Lieu::class,
            ])*/


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,

        ]);
    }
}
