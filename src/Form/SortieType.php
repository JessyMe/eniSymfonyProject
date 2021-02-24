<?php

namespace App\Form;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('datedebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'widget' => 'choice',
            ])
            ->add('datecloture', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'widget' => 'choice',
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places :',
                'attr' => ['class' => 'form-control',
                    'placeholder' => '20']
            ])
            ->add('duree', IntegerType::class, [
                'attr' => ['class' => 'form-control',
                    'placeholder' => '90']
            ])
            ->add('descriptionInfos', TextareaType::class, [
                'label' => 'Description et infos :',
                'attr' => ['class' => 'form-control']

            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'placeholder' => 'Sélectionner la ville',
                'label' => 'ville',
                'mapped' => false,
            ]);



        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event)
            {
                $form = $event->getForm();

                $form->getParent()->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'placeholder' => 'Sélectionner le lieu',
                    'label' => 'Lieu',
                    'choices' => $form->getData()->getLieux()


                ]);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)
            {
                $form = $event->getForm();
                $data = $event->getData();
                $lieu = $data->getLieu();

                if ($lieu)
                {
                    $form->get('ville')->setData($lieu->getVille());

                    $form->add('lieu', EntityType::class, [
                        'class' => Lieu::class,
                        'placeholder' => 'Sélectionner le lieu',
                        'label' => 'Lieu',
                        'choices' => $lieu->getVille()->getLieux()

                    ]);

                }



            }
        );

    }

            // pour ajouter un nouveau lieu, utiliser partie 1 doc Form Based on the Underlying Data


            /*->add('campus')
            };
            ->add('ville', EntityType::class,
                [
                    'class' => Ville::class,
                    'choice_label' => function ($ville) {
                        return $ville->getNomVille($ville);
                    },
                    'mapped' => false,
                    'placeholder' => 'Selectionner la ville',
                    'required' => false,

                ]);

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'placeholder' => 'Sélectionner le lieu',
                    'label'=> 'Lieu',
            ]);
            }


        );
    }

            //->add('lieu', LieuType::class)
           /* ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'label'=>'Lieu',
                'choice_label'=>function($lieu){
                    return $lieu->getNomLieu();
                },
                'placeholder'=>'Choisir un lieu'])

        /*->add('save', SubmitType::class, [
            'label'=> 'Enregistrer'
        ])
        ->add('saveAndAdd', SubmitType::class, [
            'label'=> 'Publier',
        ])
        ->add('Annuler', ResetType::class);
->add('save', SubmitType::class, [
'label'=> 'Enregistrer'
])
->add('saveAndAdd', SubmitType::class, [
'label'=> 'Publier',
])
->add('Annuler', ResetType::class)*/



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,

        ]);
    }
}
