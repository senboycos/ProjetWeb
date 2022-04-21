<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,['label'=>'Libellé : ', 'attr'=> ['placeholder'=>'nom du produit']])
            ->add('prix', IntegerType::class,['label'=>'Prix : ', 'attr'=> ['placeholder'=>'2']])
            ->add('quantite', IntegerType::class,['label'=>'Quantité : ', 'attr'=> ['placeholder'=>'1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
