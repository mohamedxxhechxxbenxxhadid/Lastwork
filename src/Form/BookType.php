<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                ],])
            ->add('publicationDate')
            ->add('published')
            ->add('author',EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username', // Replace 'name' with the actual property you want to display
                'multiple' => false, // Set to true if you want to allow selecting multiple classrooms
                'expanded' => false, // Set to true if you want checkboxes instead of a select dropdown
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
                'published' => true, // Set it to true or false as needed
        ]);
    }
}
