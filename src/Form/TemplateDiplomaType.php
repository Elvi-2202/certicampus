<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\TemplateDiploma;
 
class TemplateDiplomaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'label' => 'Contenu du template',
                'attr'  => ['rows' => 15],
            ]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TemplateDiploma::class]);
    }
}