<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => ['autocomplete' => 'email'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your email',
                ]),
            ],
        ])
        ->add('fullname')
            ->add(
                'dob',
                DateType::class,
                [
                    'required' => true,
                    'widget' => 'single_text'  //input type="date" (HTML)
                ]
            )
            ->add('phone', TextType::class,
            [
                'attr' => [
                    'minlength' => 9,
                ]
            ])
            ->add('image', FileType::class,
            [
                'label' => 'User Image',
                'data_class' => null,
                'required' => is_null ($builder->getData()->getImage())
            ])
            ->add('gender', ChoiceType::class,
            [
                'choices' => [
                    ''=>'',
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Multi-gender' => 'Multi-gender',
                    
                ],
                'expanded' => False,    
                'multiple' => false 
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Agree terms_',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
