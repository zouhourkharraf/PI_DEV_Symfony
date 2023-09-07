<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_cour', null, [
                'label' => 'Date du Cours',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('temps_cour', null, [
                'label' => 'Temps du Cours',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('titre_cour', null, [
                'label' => 'Titre du Cours',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('utilisateur', null, [
                'label' => 'Utilisateur',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('matiere', null, [
                'label' => 'Matière',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Fichier PDF',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control-file', // Bootstrap class for file input
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                    ]),
                ],
            ])
            ->add("Ajouter", SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary', // Bootstrap class for the submit button
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
