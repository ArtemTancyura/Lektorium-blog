<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\DataTransformer\TagsToCollectionTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('shortText', TextareaType::class)
            ->add('longText', TextareaType::class)
            ->add('tags', CollectionType::class, [
                'entry_type' => TagType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'required' => false
            ])
            ->add('image', FileType::class, [
                'label' => false,
                'data_class' => null
            ]);
        
        $builder
            ->get('tags')
            ->addModelTransformer(new TagsToCollectionTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Article',
        ]);
    }
}
