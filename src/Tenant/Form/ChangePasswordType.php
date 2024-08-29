<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends StyledType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('old_password', PasswordType::class, [
            'row_attr' => ['class' => 'w-full'],
            'label_attr' => ['class' => self::LABEL_ATTR],
            'attr' => ['class' => self::INPUT_ATTR],
            'constraints' => [
                new UserPassword(),
            ],
            'mapped' => false,
            'required' => true,
        ]);

        $builder
            ->add('plainPassword', RepeatedType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => self::INPUT_ATTR,
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'label' => 'New password',
                    'label_attr' => ['class' => self::LABEL_ATTR],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'label_attr' => ['class' => self::LABEL_ATTR],
                ],
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
