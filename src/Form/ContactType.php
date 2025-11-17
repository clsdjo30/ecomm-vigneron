<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'votre.email@exemple.com',
                    'class' => 'form-control',
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'placeholder' => '+33 6 12 34 56 78',
                    'class' => 'form-control',
                ],
            ])
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'choices' => ContactMessage::getSubjectChoices(),
                'placeholder' => 'Sélectionnez un sujet',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message...',
                    'class' => 'form-control',
                    'rows' => 5,
                ],
            ])
            // Honeypot anti-spam (champ caché)
            ->add('website', TextType::class, [
                'label' => false,
                'required' => false,
                'mapped' => true,
                'attr' => [
                    'class' => 'd-none',
                    'tabindex' => '-1',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
