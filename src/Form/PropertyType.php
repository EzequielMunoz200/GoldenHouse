<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('surface', NumberType::class)
            ->add('rooms', NumberType::class)
            ->add('bedrooms', NumberType::class)
            ->add('floor', NumberType::class)
            ->add('price', NumberType::class)
            ->add('heat', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('city', TextType::class)
            ->add('address', TextType::class)
            ->add('postal_code', TextType::class)
            ->add('is_sold', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'validate' => 'no-validate'
                ]
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'row_attr' => [
                    'required' => 'none',
                ],
            ])
            ->add('images', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'label' => false,
                'constraints' => [
                    //upload multiple
                    new All([
                        new Image([
                            'mimeTypes' => 'image/jpeg',
                            'maxSize' => '1024k',
                            //requires PHP GD extension
                            'detectCorrupted' => true
                        ])
                    ])

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms',
        ]);
    }


    private function getChoices()
    {
        $choices = Property::HEAT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
