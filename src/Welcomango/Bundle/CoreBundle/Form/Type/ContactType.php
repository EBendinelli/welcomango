<?php

namespace Welcomango\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

use Welcomango\Model\User;

/**
 * ContactType Form class
 */
class ContactType extends AbstractType
{

    /**
     * @param User $user
     * @param String $category
     */
    public function __construct(User $user = null, $category = '')
    {
        $this->user = $user;
        $this->category = $category;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = array(
            'Bug' => 'Bug',
            'Portrait' => 'Portrait',
            'Question' => 'Question',
            'Suggestion' => 'Suggestion',
            'Issue' => 'Issue',
            'Contact' => 'Contact',
            'Other' => 'Other'
        );

        if($this->user){
            $builder
                ->add('name', 'text', [
                    'attr' => [
                        'placeholder' => 'Who are you?',
                        'pattern'     => '.{2,}' //minlength
                    ],
                    'data' => $this->user->getFullName(),
                    // 'disabled' => true,
                ])
                ->add('email', 'email', [
                    'attr' => [
                        'placeholder' => 'Can be useful if we want to answer you right?'
                    ],
                    'data' => $this->user->getEmail(),
                    // 'disabled' => true,
                ]);
        }else{
            $builder
                ->add('name', 'text', [
                    'attr' => [
                        'placeholder' => 'Who are you?',
                        'pattern'     => '.{2,}' //minlength
                    ]
                ])
                ->add('email', 'email', [
                    'attr' => [
                        'placeholder' => 'Can be useful if we want to answer you right?'
                    ]
                ]);
        }

        if($this->category){
            $builder->add('category', 'choice', [
                'choices' => $categories,
                'data' => $this->category,
            ]);
        }else{
            $builder->add('category', 'choice', [
                'choices' => $categories,
            ]);
        }


        $builder->add('message', 'textarea', [
                'attr' => [
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'Time to tell us a bit more...'
                ]
            ])
            ->add('page_from', 'hidden')
            ->add('submit','submit', [
                'attr' => [
                    'class' => 'btn-complete btn-lg '
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'Name should not be blank.')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'Email should not be blank.')),
                new Email(array('message' => 'Invalid email address.'))
            ),
            'subject' => array(
                new NotBlank(array('message' => 'Subject should not be blank.')),
                new Length(array('min' => 3))
            ),
            'message' => array(
                new NotBlank(array('message' => 'Message should not be blank.')),
                new Length(array('min' => 5))
            ),
        ));

    }

    public function getName()
    {
        return 'front_contact';
    }
}