<?php

namespace Welcomango\Bundle\MediaBundle\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Welcomango\Model\Media;

/**
 * SimpleMediaType Form class
 */
class SimpleMediaType extends AbstractType
{
    /**
     * @var Media $oldFile
     */
    private $oldFile;

    /**
     * @param Media $oldFile
     */
    function __construct(Media $oldFile)
    {
        $this->oldFile = $oldFile;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media_photo', 'file', [
            'required' => false,
            'mapped'   => false,
            'label'    => 'form.profile.uploadPhoto',
        ]);

        $builder->add('originalFilename', HiddenType::class, [
            'required' => false,
            'label'    => false,
        ]);

        $builder->add('oldFilename', HiddenType::class, [
            'required' => false,
            'data'     => $this->oldFile->getOriginalFilename(),
            'label'    => false,
            'mapped'    => false,
        ]);

        $builder->add('oldFilePath', HiddenType::class, [
            'required' => false,
            'data'     => $this->oldFile->getPath(),
            'label'    => false,
            'mapped'    => false,
        ]);

        $builder->add('deleteOldFile', HiddenType::class, [
            'required' => false,
            'data'     => false,
            'mapped'   => false,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Welcomango\Model\Media',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'simple_media_form';
    }
}
