<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobDescription', TextareaType::class, [
                'label' => 'Descrição do cargo/serviço',
                'required' => false,
                'attr' => ['rows' => 3],
            ])
            ->add('defaultDailyRate', NumberType::class, [
                'label' => 'Valor diário padrão',
                'required' => false,
                'scale' => 2,
                'html5' => true,
            ])
            ->add('defaultDailyRateCurrency', ChoiceType::class, [
                'label' => 'Moeda padrão',
                'required' => false,
                'placeholder' => 'Selecione',
                'choices' => [
                    'CAD' => 'CAD',
                    'USD' => 'USD',
                    'BRL' => 'BRL',
                    'EUR' => 'EUR',
                    'GBP' => 'GBP',
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Nova senha',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Length(min: 6, minMessage: 'A senha deve ter no mínimo {{ limit }} caracteres.'),
                ],
                'attr' => [
                    'placeholder' => 'Preencha apenas se quiser alterar a senha',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
