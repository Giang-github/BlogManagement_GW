<?php

namespace App\Form;

use App\Entity\Podcast;
use App\Entity\Course;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class PodcastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Podcast title',
                    'attr' => [
                        'minlength' => 3,
                        'maxlength' => 30
                    ]
                ]
            )
            ->add(
                'image',
                FileType::class,
                [
                    'label' => 'Podcast image',
                    'data_class' => null,
                    'required' => is_null($builder->getData()->getImage())
                ]
            )

            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => ' Podcast Content',
                    'row_attr' => [
                        'class' => 'text_area',
                        'minlength' => 8,
                        'maxlength' => 10000,
                    ]
                ]
            )

            ->add(
                'audio',
                FileType::class,
                [
                    'label' => ' Podcast audio',
                    'row_attr' => [
                        'maxSize' => '256M',
                        'mimeTypes' => [
                            'application/octet-stream',
                            'audio/mpeg',
                            'audio/mp3'
                        ]
                    ]
                ]
            )

            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'Category',
                    'required' => false,
                    'class' => Category::class,
                    'choice_label' => 'title',
                    'multiple' => false,
                    'expanded' => false
                ]
            )

            ->add(
                'course',
                EntityType::class,
                [
                    'label' => 'Course',
                    'required' => false,
                    'class' => Course::class,
                    'choice_label' => 'title',
                    'multiple' => false,
                    'expanded' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Podcast::class,
        ]);
    }
}
