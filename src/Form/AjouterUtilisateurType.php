<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Mime\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;


class AjouterUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_util', TextType::class, ['attr' => ['autofocus' => true]])
            ->add('prenom_util', TextType::class, ['help' => 'Le nom et le prénom ne doivent pas être identiques'])
            ->add('age_util', IntegerType::class, ['constraints' => new GreaterThanOrEqual(['value' => 22, 'message' => 'Vous devez avoir au moins 22 ans pour créer un compte enseignant'])]) // voir (1)
            ->add('genre_util', ChoiceType::class, ['choices' => ['Homme' => 'H', 'Femme' => 'F'], 'multiple' => false, 'expanded' => true])
            ->add('email_util', EmailType::class, ['help' => 'Nous utiliserons votre adresse e-mail pour récupérer votre mot de passe et pour toute autre fonctionnalité.'])
            ->add('mot_de_passe_util', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'Les mots de passe ne sont pas identiques', 'options' => ['attr' => ['class' => 'password-field']], 'first_options'  => ['label' => 'Mot de passe : '], 'second_options' => ['label' => 'Confirmation : ', 'help' => 'Saisissez de nouveau le mot de passe choisi'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner ce champ']), new Length(['min' => 8, 'minMessage' => 'Le mot de passe doit comporter au moins 8 caractères']), new Regex(['pattern' => "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/", 'match' => true, 'message' => 'le mot de passe doit comporter au moins une lettre majuscule lettre miniscule, et un chiffre '])]])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'captcha invalide, veuillez réessayer',
                    ]),
                ],
            ))
            ->add('Valider', SubmitType::class)
            ->add('Annuler', ResetType::class);
    }

    //(1) :on a utilisé la contrainte dans le formulaire et non pas dans l'entité parce que l'utilisateur élève a une contrainte différente
    // pour l'attribut age qu'on va la manipuler au niveau de son formulaire NB: c'est la seule contrainte qui lui est différente par rapport à l'enseignant

    //    ->add('mot_de_passe_util', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'Les mots de passe ne sont pas identiques', 'options' => ['attr' => ['class' => 'password-field']], 'first_options'  => ['label' => 'Mot de passe : ', 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner ce champ']), new Length(['min' => 8, 'minMessage' => 'Le mot de passe doit comporter au moins 8 caractères']), new Regex(['pattern' => "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/", 'match' => true, 'message' => 'le mot de passe doit comporter au moins une lettre majuscule lettre miniscule, et un chiffre '])]], 'second_options' => ['label' => 'Confirmation : ']])
    // ->add('confirmation_mp', PasswordType::class, ['mapped' => false, 'attr' => ['placeholder' => 'confirmez votre mot de passe'], 'help' => 'Saisissez de nouveau le mot de passe choisi', 'constraints' => new EqualTo(['value' => 'this.get('mot_de_passe_util').getData()', 'message' => 'Les mots de passe ne sont pas identiques'])]) // ['mapped'=>false] signifie que ce champs n'est associé à aucun attribut de cette entité
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
