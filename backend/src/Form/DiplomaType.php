<?php

use App\Entity\Diploma;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
 
class DiplomaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file_url', TextType::class, ['label' => 'URL du fichier'])
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