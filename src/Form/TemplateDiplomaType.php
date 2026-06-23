<?php
namespace App\Form;

use App\Entity\TemplateDiploma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateDiplomaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('studentName', TextType::class, [
                'label' => 'Nom de l\'étudiant',
                'attr'  => ['placeholder' => 'Ex: Jean Dupont'],
            ])
            ->add('schoolName', TextType::class, [
                'label' => 'Intitulé de la formation',
                'attr'  => ['placeholder' => 'Ex: École supérieure d\'art'],
            ])
            ->add('directorName', TextType::class, [
                'label' => 'Nom du directeur',
                'attr'  => ['placeholder' => 'Ex: François Dubois'],
            ])
            ->add('assistantDirectorName', TextType::class, [
                'label' => 'Nom du directeur adjoint',
                'attr'  => ['placeholder' => 'Ex: Jacques Leclair'],
            ])
            ->add('identifier', TextType::class, [
                'label' => 'Identifiant',
                'attr'  => ['placeholder' => 'Ex: XXXXXXXX'],
            ])
            ->add('certificateDate', DateType::class, [
                'label'  => 'Date de certification',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TemplateDiploma::class]);
    }
}