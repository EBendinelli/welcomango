<?php

namespace Welcomango\Bundle\CurrencyBundle\Form\Type;

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

use Welcomango\Model\Currency;

/**
 * AdminCurrencyType Form class
 */
class AdminCurrencyType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', 'text', ['label' => 'form.currency.name'])
            ->add('code', 'text', ['label' => 'form.currency.code'])
            ->add('symbol', 'text', ['label' => 'form.currency.symbol'])
            ->add('published', 'checkbox', ['label' => 'form.currency.published'])
            ->add('position', 'text', ['label' => 'form.currency.position'])
            ->add('format', 'text', ['label' => 'form.currency.format'])
            ->add('rate', 'text', ['label' => 'form.currency.rate']);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Welcomango\Model\Currency',
            'translation_domain' => 'currency'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_currency';
    }
}
