<?php

namespace App\Form;

use App\Entity\Evennement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class EvennementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date_debut', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new Expression([
                        'expression' => 'value <= this.getParent().get("date_fin").getData()',
                        'message' => 'The start date must be before or equal to the end date.',
                    ]),
                ],
            ])
            ->add('date_fin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('lieu')
            ->add('nbParticipant')
            ->add('sponsor')
            ->add('image', FileType::class, [
                'label' => 'image (PNG file) ',
                'mapped' => true,
                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '51200k',
                        'mimeTypes' => [
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG file'
                    ])
                ],
            ]);
        $builder->get('image')->addModelTransformer(new Transformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evennement::class,
        ]);
    }
}
