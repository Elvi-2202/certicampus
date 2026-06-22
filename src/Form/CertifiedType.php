<?php

namespace App\Form;

use App\Entity\Certified;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertifiedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom complet',
                'attr'  => ['placeholder' => 'Ex: Jean Dupont'],
            ])
            ->add('grade', TextType::class, [
                'label' => 'Mention',
                'attr'  => ['placeholder' => 'Ex: Très bien'],
            ])
            ->add('graduationDate', DateType::class, [
                'label'  => 'Date de diplôme',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Certified::class,
        ]);
    }
}