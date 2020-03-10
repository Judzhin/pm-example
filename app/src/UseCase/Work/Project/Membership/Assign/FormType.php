<?php

namespace App\UseCase\Work\Project\Membership\Assign;


use App\Entity\Work\Member;
use App\ReadModel\Work\MemberFetcher;
use App\ReadModel\Work\Project\DepartmentFetcher;
use App\ReadModel\Work\Project\RoleFetcher;
use App\Repository\MemberRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormType
 *
 * @package App\UseCase\Work\Project\Membership\Assign
 */
class FormType extends AbstractType
{
    /** @var MemberFetcher */
    private $members;

    /** @var DepartmentFetcher */
    private $departments;

    /** @var RoleFetcher */
    private $roles;

    /**
     * FormType constructor.
     *
     * @param MemberRepository $members
     * @param DepartmentFetcher $departments
     * @param RoleFetcher $roles
     */
    public function __construct(MemberRepository $members, DepartmentFetcher $departments, RoleFetcher $roles)
    {
        $this->members = $members;
        $this->departments = $departments;
        $this->roles = $roles;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $members = [];
        // foreach ($this->members->activeGroupedList() as $item) {
        //     $members[$item['group']][$item['name']] = $item['id'];
        // }

        /** @var Member $member */
        foreach ($this->members->findAll() as $member) {

            $members[$member->getGroup()->getName()] = [$member->getName() => $member->getId()];
        }

        dump($members); die;

        $builder
            ->add('member', Type\ChoiceType::class, [
                'choices' => $members,
            ])
            ->add('departments', Type\ChoiceType::class, [
                'choices' => array_flip($this->departments->listOfProject($options['project'])),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('roles', Type\ChoiceType::class, [
                'choices' => array_flip($this->roles->allList()),
                'expanded' => true,
                'multiple' => true,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Command::class,
        ));
        $resolver->setRequired(['project']);
    }
}