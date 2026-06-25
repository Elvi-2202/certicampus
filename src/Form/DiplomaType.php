<?php

namespace App\Form;

use App\Entity\Diploma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiplomaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file_url', TextType::class, ['label' => 'URL du fichier'])
            // Ajout du champ Niveau juste après le fichier
            ->add('level', TextType::class, [
                'label' => 'Niveau (ex: Bac+3, Master...)',
                'required' => true
            ])
            ->add('generated_at', DateTimeType::class, [
                'label'  => 'Date de génération',
                'widget' => 'single_text',
            ])
            ->add('is_valid', CheckboxType::class, [
                'label'    => 'Diplôme valide',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Diploma::class]);
    }
}