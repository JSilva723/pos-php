<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tenant\Entity\User;

class UserType extends StyledType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'row_attr' => ['class' => 'w-full'],
                'label_attr' => ['class' => self::LABEL_ATTR],
                'attr' => ['class' => self::INPUT_ATTR],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 180]),
                ],
            ]);

        $builder
            ->add('isDarkTheme', CheckboxType::class, [
                'label' => 'Theme',
                'attr' => ['class' => 'form-check'],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
