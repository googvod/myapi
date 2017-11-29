<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
/**
 * Class AbstractHubType
 * @package library\Form
 */
class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', Type\TextType::class, [
                'required' => true,
                'trim' => true,
                'constraints' => [new NotBlank()]
            ])
            ->add('completed', Type\ChoiceType::class, [
                'required' => false,
                'choices' => [1, 0],
                'constraints' => [new NotBlank()]
            ]);
    }
}