<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReinitialiserMPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mot_de_passe', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'Les mots de passe ne sont pas identiques', 'options' => ['attr' => ['class' => 'password-field']], 'first_options'  => ['label' => 'Le nouveau mot de passe : '], 'second_options' => ['label' => 'Confirmation : ', 'help' => 'Saisissez de nouveau le mot de passe choisi'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner ce champ']), new Length(['min' => 8, 'minMessage' => 'Le mot de passe doit comporter au moins 8 caractères']), new Regex(['pattern' => "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/", 'match' => true, 'message' => 'le mot de passe doit comporter au moins une lettre majuscule lettre miniscule, et un chiffre '])]])
            ->add('Confirmer', SubmitType::class)
            ->add('Annuler', ResetType::class);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
