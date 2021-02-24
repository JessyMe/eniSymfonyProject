<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegisterType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo : ',
                'attr' => ['class' =>'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom : ',
                'attr' => ['class' =>'form-control']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom : ',
                'attr' => ['class' =>'form-control']
            ])
            ->add('telephone', IntegerType::class, [
                'label' => 'Telephone : ',
                'attr' => ['class' =>'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
                'attr' => ['class' =>'form-control']
            ])
            ->add('password',  RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'attr' => ['class' =>'form-control'],
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' =>'form-control']],
                'second_options' => ['label' => 'Confirmer', 'attr' => ['class' =>'form-control']],
                ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nomCampus',
                'label' => 'Campus : ',
                'attr' => ['class' =>'form-control']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo : ',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
//                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            ]
//                        'mimeTypesMessage' => 'Entrez une photo valide',
                    ])],
                'attr' => ['class' =>'form-control',
//                    'value' => '{{ user.photo }}',
                    'placeholder' => 'Photo'
                ]
            ])
        ;
        $builder->get('photo')->addModelTransformer(new CallBackTransformer(
            function($photo) {
                return null;
            },
            function($photo) {
                return $photo;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
