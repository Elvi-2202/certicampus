<?php

use App\Entity\Training;
 
class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, ['label' => 'Nom de la formation'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Training::class]);
    }
}