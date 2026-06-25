<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\Subscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, ['label' => 'Nom de l\'abonnement'])
            ->add('price', MoneyType::class, ['label' => 'Prix', 'currency' => 'EUR'])
            ->add('duration', TextType::class, ['label' => 'Durée (ex: 1 mois, 1 an)'])
            ->add('isActive', CheckboxType::class, ['label' => 'Actif', 'required' => false])
            ->add('school', EntityType::class, [
                'class' => School::class,
                'choice_label' => 'label',
                'label' => 'École associée',
                'required' => false,
                'placeholder' => 'Aucune école',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Subscription::class]);
    }
}