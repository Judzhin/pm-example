<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Filter;

use App\Model\Work\Member\Status;
use App\Repository\GroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormType
 * @package App\UseCase\Work\Member\Filter
 */
class FormType extends AbstractType
{
    /** @var GroupRepository */
    private $repository;

    /**
     * FormType constructor.
     * @param GroupRepository $repository
     */
    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Name',
                    'onchange' => 'this.form.submit()',
                ]
            ])
            ->add('email', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email',
                    'onchange' => 'this.form.submit()',
                ]
            ])
            ->add('group', Type\ChoiceType::class, [
                'choices' => array_flip($this->repository->assoc()),
                'required' => false,
                'placeholder' => 'All groups',
                'attr' => ['onchange' => 'this.form.submit()']
            ])
            ->add('status', Type\ChoiceType::class, [
                'choices' => [
                    'Active' => Status::STATUS_ACTIVE,
                    'Archived' => Status::STATUS_ARCHIVED,
                ],
                'required' => false,
                'placeholder' => 'All statuses',
                'attr' => ['onchange' => 'this.form.submit()']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

}