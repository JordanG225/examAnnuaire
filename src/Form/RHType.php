<?php

namespace App\Form;

use App\Entity\RH;
use Doctrine\DBAL\Types\TextType;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

class RHType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=> ['class'=> 'form-control']
            ])
            ->add('prenom', TextType::class,[
                'attr'=> ['class'=> 'form-control']
            ])

            ->add('photo', FileType::class,[
                'label'=> 'photo',
                'attr'=> [
                    'class' => 'mt-2'
                ],
                'mapped'=> false,
                'required'=> false,
                'constraints'=> [
                    new ConstraintsFile([
                        'maxeSize'=>'1g',
                        'mimeTypes'=> ['photo/*'],
                        'mimeTypesMessage'=>'Nous acceptons que les photos',
                        'maxSizeMessage'=> 'Le fichier est trop lourd'
                    ])
                ]

            ])
            ->add('typeContrat', TextType::class,[
                'attr'=> ['class'=> 'form-control']
            ])
            ->add('supression', TextType::class,[
                'attr'=> ['class'=> 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RH::class,
        ]);
    }
}
