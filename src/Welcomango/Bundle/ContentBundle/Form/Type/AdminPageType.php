<?php

namespace Welcomango\Bundle\ContentBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

use Welcomango\Model\Page;

/**
 * AdminPageType Form class
 */
class AdminPageType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $publicationStatus = ['published' => 'published', 'pending' => 'pending', 'deleted' => 'deleted'];

        $builder->add('title', 'text', ['label' => 'form.page.title']);
        $builder->add('content', 'textarea', [
            'label' => 'form.page.content',
        ]);
        $builder->add('publication_status', 'choice', [
            'choices' => $publicationStatus,
            'label' => 'form.page.content'
        ]);

        $builder->add('category', 'entity', array(
            'class' => 'Model:Category',
            'property' => 'name',
        ));

        $builder->add('author', 'entity', array(
            'class' => 'Model:User',
            'property' => 'fullname',
        ));

        $builder->add('banner', 'entity', array(
            'class' => 'Model:Media',
            'property' => 'id',
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Page',
            'translation_domain' => 'admin'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_page';
    }
}
