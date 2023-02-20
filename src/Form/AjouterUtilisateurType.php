<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotEqualTo;

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
            ->add('mot_de_passe_util', PasswordType::class)
            ->add('confirmation_mp', PasswordType::class, ['mapped' => false, 'attr' => ['placeholder' => 'confirmez votre mot de passe'], 'help' => 'Saisissez de nouveau le mot de passe choisi']) // ['mapped'=>false] signifie que ce champs n'est associé à aucun attribut de cette entité
            ->add('Valider', SubmitType::class)
            ->add('Annuler', ResetType::class);
    }

    //(1) :on a utilisé la contrainte dans le formulaire et non pas dans l'entité parce que l'utilisateur élève a une contrainte différente
    // pour l'attribut age qu'on va la manipuler au niveau de son formulaire NB: c'est la seule contrainte qui lui est différente par rapport à l'enseignant

    // ->add('confirmation_mp', PasswordType::class, ['mapped' => false, 'attr' => ['placeholder' => 'confirmez votre mot de passe'], 'help' => 'Saisissez de nouveau le mot de passe choisi', 'constraints' => new EqualTo(['value' => '$this->getData()->getMotDePasseUtil()', 'message' => 'Les mots de passe ne sont pas identiques'])]) // ['mapped'=>false] signifie que ce champs n'est associé à aucun attribut de cette entité
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
