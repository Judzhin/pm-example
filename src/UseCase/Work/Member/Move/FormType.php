<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Move;

use App\Repository\GroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormType
 * @package App\UseCase\Work\Member\Move
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
     * @inheritdoc
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('group', Type\ChoiceType::class, [
                'choices' => array_flip($this->repository->assoc())
            ]);
    }

    /**
     * @inheritdoc
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
