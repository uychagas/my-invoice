<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nome da empresa'])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo',
                'choices' => [
                    'Minha empresa (PJ emissora)' => Company::TYPE_PROVIDER,
                    'Empresa destinatária' => Company::TYPE_CLIENT,
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail da empresa',
                'required' => false,
            ])
            ->add('taxId', null, ['label' => 'Tax ID / CNPJ', 'required' => false])
            ->add('countryCode', CountryType::class, ['label' => 'País'])
            ->add('address', TextareaType::class, [
                'label' => 'Endereço',
                'required' => false,
                'attr' => ['rows' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
