<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Validator\Constraints\GreaterThan;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomact')
            ->add('dateact', DateType::class, [
                'constraints' => [
                    new GreaterThan('today')
                ]
            ])
            ->add('nbparticipants', IntegerType::class, [
                'constraints' => [
                    new Positive()
                ]
            ])
            ->add('positionact')
            ->add('type')
            #->add('listeutilisateurs')
            ->add('confirm',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
