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

class AjouterUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_util', TextType::class)
            ->add('prenom_util', TextType::class)
            ->add('age_util', IntegerType::class)
            ->add('genre_util', ChoiceType::class, ['choices' => ['Homme' => 'H', 'Femme' => 'F'], 'multiple' => false, 'expanded' => true])
            ->add('email_util', EmailType::class)
            ->add('mot_de_passe_util', PasswordType::class)
            ->add('confirmation_mp', PasswordType::class, ['mapped' => false]) // ['mapped'=>false] signifie que ce champs n'est associé à aucun attribut de cette entité
            ->add('Valider', SubmitType::class)
            ->add('Annuler', ResetType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
