<?php

use App\Entity\Speciality;
 
class SpecialityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, ['label' => 'Nom de la spécialité'])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Speciality::class]);
    }
}