<?php
namespace App\Form;

use App\Entity\Certified;
use App\Entity\TemplateDiploma;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('certified', EntityType::class, [
                'class' => Certified::class,
                'choice_label' => 'label',
                'label' => 'Étudiant certifié',
                'mapped' => false,
                'required' => true,
            ])
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