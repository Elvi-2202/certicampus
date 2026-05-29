<?php

use App\Entity\Certified;
use Symfony\Component\Form\Extension\Core\Type\DateType;
 
class CertifiedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, ['label' => 'Nom complet'])
            ->add('grade', TextType::class, ['label' => 'Mention'])
            ->add('graduation_date', DateType::class, [
                'label'  => 'Date de diplôme',
                'widget' => 'single_text',
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Certified::class]);
    }
}
