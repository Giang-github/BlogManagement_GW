<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Course title',
                    'attr' => [
                        'minlength' => 3,
                        'maxlength' => 30
                    ]
                ]
            )
            // ->add(
            //     'stardate',
            //     DateType::class,
            //     [
            //         'required' => true,
            //         'widget' => 'single_text'  //input type="date" (HTML)
            //     ]
            // )
            // // ->add(
            // //     'enddate',
            // //     DateType::class,
            // //     [
            // //         'required' => true,
            // //         'widget' => 'single_text'  //input type="date" (HTML)
            // //     ]
            // // )
            // ->add('price', MoneyType::class,
            // [
            //     'label' => 'Course price',
            //     'currency' => 'USD'
            // ])
            ->add(
                'description',TextareaType::class,
                [
                    'label' => ' Course description',
                    'row_attr' => [
                        'class' => 'text_area',
                        'minlength' => 8,
                        'maxlength' => 250,
                    ]
            ])
            ->add('image', FileType::class,
            [
                'label' => 'Course Image',
                'data_class' => null,
                'required' => is_null ($builder->getData()->getImage())
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
