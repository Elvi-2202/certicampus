<?php

use App\Entity\TemplateDiploma;
 
class TemplateDiplomaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Contenu du template',
                'attr'  => ['rows' => 15],
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TemplateDiploma::class]);
    }
}