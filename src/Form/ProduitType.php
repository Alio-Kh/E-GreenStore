<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prixUnitaire')
            ->add('stock')
            ->add('description')
            ->add('dateProduction')
            ->add('image')
            ->add('dateAjout')
            ->add('isBio')
            ->add('information')
            ->add('libelle')
            ->add('updatedAt')
            ->add('unite')
            ->add('market')
            ->add('tva')
            ->add('promotion')
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
