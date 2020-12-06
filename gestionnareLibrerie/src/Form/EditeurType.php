<?php

namespace App\Form;

use App\Entity\Editeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEditeur',TextType::class,['attr'=>['class'=>'form-control'],'label'=>"Nom Editeur"])
            ->add('pays',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Pays'])
            ->add('adresse',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Adresse'])
            ->add('telephone',NumberType::class,['attr'=>['class'=>'form-control'],'label'=>'Télephone'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Editeur::class,
        ]);
    }
}
