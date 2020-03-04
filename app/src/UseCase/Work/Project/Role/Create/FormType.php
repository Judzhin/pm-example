<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Project\Role\Create;

use App\Model\Work\Project\Permission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zend\Stdlib\ArrayUtils;

/**
 * Class FormType
 * @package App\UseCase\Work\Project\Role\Create
 */
class FormType extends AbstractType
{
    /**
     * @inheritdoc
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Type\TextType::class)
            ->add('permissions', Type\ChoiceType::class, [
                'choices' => array_combine(Permission::values(), Permission::values()),
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                // 'translation_domain' => '',
            ])
        ;
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