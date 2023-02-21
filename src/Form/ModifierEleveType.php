<?php

namespace App\Form;


use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ModifierEleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_util', TextType::class, ['attr' => ['autofocus' => true]])
            ->add('prenom_util', TextType::class, ['help' => 'Le nom et le prénom ne doivent pas être identiques'])
            ->add('age_util', IntegerType::class, ['constraints' => new GreaterThanOrEqual(['value' => 5, 'message' => 'Vous devez avoir au moins 5 ans pour créer un compte élève'])]) // voir (1)
            ->add('genre_util', ChoiceType::class, ['choices' => ['Homme' => 'H', 'Femme' => 'F'], 'multiple' => false, 'expanded' => true])
            ->add('email_util', EmailType::class, ['help' => 'Nous utiliserons votre adresse e-mail pour récupérer votre mot de passe et pour toute autre fonctionnalité.'])
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
