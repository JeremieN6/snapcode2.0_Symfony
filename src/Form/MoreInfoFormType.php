<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
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
        $serviceType = array("role1","role2","role3");
        $builder
            ->add('serviceType', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Création de site surperformant' => "creation_de_site_surperformant",
                    'Autre' => "autre"
                    ],
                    'expanded' => true, // Active cette option pour afficher les checkboxes au lieu d'une liste déroulante
                    'multiple' => true, // Active cette option si tu veux permettre la sélection de plusieurs options
                    'required' => false, // À ajuster selon tes besoins
                    'attr' => [
                        'class' => ''
                    ]
            ])
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'ex. Jérémie Maole',
                    'class' => 'form-text-field w-input'
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Entrez votre prénom',
                'attr' => [
                    'placeholder' => 'ex. Jérémie Maole'
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Entrez votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'ex. 0607080910'
                ],
            ])
            ->add('website', TextType::class, [
                'label' => 'Entrez le lien de votre site web (facultatif)',
                'attr' => [
                    'placeholder' => 'ex. jeremiecode.fr'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Entrez votre e-mail',
                'attr' => [
                    'placeholder' => 'ex. contact.snapcode@jeremiecode.fr'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Comment pouvons nous vous aidez ?',
                'attr' => [
                    'placeholder' => 'ex. jeremiecode.fr'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
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
