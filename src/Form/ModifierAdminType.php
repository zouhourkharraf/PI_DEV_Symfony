<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ModifierAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo_util', TextType::class, ['constraints' => new NotBlank(['message' => 'Veuillez renseigner ce champ'])])
            ->add('email_util', EmailType::class, ['help' => 'Nous utiliserons votre adresse e-mail pour récupérer votre mot de passe et pour toute autre fonctionnalité.'])
            ->add('mot_de_passe_util', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'Les mots de passe ne sont pas identiques', 'options' => ['attr' => ['class' => 'password-field']], 'first_options'  => ['label' => 'Mot de passe : '], 'second_options' => ['label' => 'Confirmation : ', 'help' => 'Saisissez de nouveau le mot de passe choisi'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner ce champ']), new Length(['min' => 8, 'minMessage' => 'Le mot de passe doit comporter au moins 8 caractères']), new Regex(['pattern' => "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/", 'match' => true, 'message' => 'le mot de passe doit comporter au moins une lettre majuscule lettre miniscule, et un chiffre '])]])
            ->add('Modifier', SubmitType::class)
            ->add('Annuler', ResetType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
