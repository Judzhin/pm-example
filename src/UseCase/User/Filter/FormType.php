<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\UseCase\User\Filter;

use App\Entity;
use App\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormType
 * @package App\UseCase\User\Filter
 */
class FormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Name'
                ]
            ])
            ->add('email', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Wait' => Entity\User::STATUS_WAIT,
                    'Active' => Entity\User::STATUS_DONE,
                    'Locked' => Entity\User::STATUS_LOCK,
                ],
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Status'
                ]
            ])
            ->add('roles', Type\ChoiceType::class, [
                'choices' => [
                    'User' => User\Role::USER,
                    'Admin' => User\Role::ADMIN,
                ],
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'All roles'
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => Request::METHOD_GET,
            'csrf_protection' => false
        ]);
    }
}
