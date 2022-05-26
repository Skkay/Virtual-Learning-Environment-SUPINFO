<?php

namespace App\Form;

use App\Enum\UploadModeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataFileUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'form.data_file_upload_type.file.label',
                'required' => true,
                'mapped' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => '.csv',
                ]
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'form.data_file_upload_type.mode.label',
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'form.data_file_upload_type.mode.choice.append' => UploadModeEnum::APPEND,
                    'form.data_file_upload_type.mode.choice.clear_and_upload' => UploadModeEnum::CLEAR_AND_UPLOAD,
                ],
                'data' => UploadModeEnum::APPEND,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
