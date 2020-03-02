<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Filter;

use App\Model\Work\Status;
use App\Repository\ProjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormType
 * @package App\UseCase\Work\Project\Filter
 */
class FormType extends AbstractType
{
    /** @var ProjectRepository */
    private $repository;

    /**
     * FormType constructor.
     *
     * @param ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     *
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
     * @inheritDoc
     *
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