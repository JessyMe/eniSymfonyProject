<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datedebut')
            ->add('duree')
            ->add('datecloture')
            ->add('nbInscriptionMax')
            ->add('descriptionInfos')
            ->add('urlPhoto')
            ->add('etat')
            ->add('organisateur')
            ->add('campus', TextType::class)
            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
