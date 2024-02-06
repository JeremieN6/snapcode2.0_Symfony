<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MoreInfoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serviceTypeSuperSite', CheckboxType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'w-checkbox form-checkbox-field'
                ],
                'required' => false,
            ])
            ->add('serviceTypeAutre', CheckboxType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'w-checkbox form-checkbox-field'
                ],
                'required' => false,
            ])
            ->add('nom', TextType::class, [
                'label' => 'Entrez votre nom',
                'attr' => [
                    'placeholder' => 'Maole',
                    'class' => 'form-text-field w-input'
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Entrez votre prénom',
                'attr' => [
                    'placeholder' => 'Jérémie',
                    'class' => 'form-text-field w-input'
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Entrez votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'ex. 0607080910',
                    'class' => 'form-text-field w-input'
                ],
            ])
            ->add('website', TextType::class, [
                'label' => 'Entrez le lien de votre site web (facultatif)',
                'attr' => [
                    'placeholder' => 'ex. jeremiecode.fr',
                    'class' => 'form-text-field w-input'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'ex. contact.snapcode@jeremiecode.fr',
                    'class' => 'form-text-field w-input'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Décrivez votre besoin ...',
                    'class' => 'form-text-field larger w-input'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer le formulaire',
                'attr' => [
                    'class' => 'next-button-slide w-button w--current'
                ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
