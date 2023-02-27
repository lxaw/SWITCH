<?php

/*
https://symfony.com/doc/current/the-fast-track/en/9-backend.html
*/
namespace App\Controller\Admin;

// actions
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use App\Entity\Food;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

// filters
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

// fields
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class FoodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Food::class;
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
            ->setSearchFields(['User']);
    }

    /*
    Configure the fields for display.
    */
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id'),
    //         TextField::new('Food Name'),
    //         TextField::new('Restaurant'),
    //         NumberField::new("ServingSize"),
    //         TextField::new("ServingSizeUnit"),
    //         NumberField::new("EnergyAmount"),
    //         TextField::new("EnergyUnit"),
    //         NumberField::new("FatAmount"),
    //         TextField::new("FatUnit"),
    //         NumberField::new("ProteinAmount"),
    //         TextField::new("ProteinUnit"),
    //         NumberField::new("PotassiumAmount"),
    //         TextField::new("PotassiumUnit"),
    //         NumberField::new("FiberAmount"),
    //         TextField::new("FiberUnit"),
    //         NumberField::new("Quantity"),
    //         AssociationField::new("User"),
    //     ];
    // }
}
