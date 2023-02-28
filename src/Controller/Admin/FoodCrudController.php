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

// response
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

// actions
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Doctrine\ORM\EntityManagerInterface;

class FoodCrudController extends AbstractCrudController
{
    public const ACTION_SAVE_CSV = "SAVE_CSV";

    // entity manager
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Food::class;
    }

    public function configureCrud(Crud $crud) : Crud
    {
        return $crud
            ->setSearchFields(['User']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $saveCsv = Action::new(self::ACTION_SAVE_CSV) 
            ->linkToCrudAction('saveFoodToCsv')
            ->setCssClass('btn btn-info')
            ->createAsGlobalAction();
        
        return $actions
            ->add(Crud::PAGE_INDEX,$saveCsv);
    }

    public function saveFoodToCsv(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $em
    ): Response {

        $userRepo = $this->em->getRepository(Food::class);
        $foods= $userRepo->findAll(); // Doctrine query
        $rows = array();
        $columns = array(
            'id',
            'date',
            'food_name',
            'restaurant',
            'food_category',
            'serving_size',
            'serving_size_unit',
            'energy_amount',
            'energy_unit',
            'fat_amount',
            'fat_unit',
            'carb_amount',
            'carb_unit',
            'protein_amount',
            'protein_unit',
            'potassium_amount',
            'potassium_unit',
            'fiber_amount',
            'fiber_unit',
            'user_id',
            'quantity',
        );
        $rows[] = implode(',',$columns);
        foreach($foods as $food){
            $data = array(
                $food->getId(),
                $food->getDate()->format('Y-m-d H:i:s'),
                $food->getFoodName(),
                $food->getRestaurant(),
                $food->getFoodCategory(),
                $food->getServingSize(),
                $food->getServingSizeUnit(),
                $food->getEnergyAmount(),
                $food->getEnergyUnit(),
                $food->getFatAmount(),
                $food->getFatUnit(),
                $food->getCarbAmount(),
                $food->getCarbUnit(),
                $food->getProteinAmount(),
                $food->getProteinUnit(),
                $food->getPotassiumAmount(),
                $food->getPotassiumUnit(),
                $food->getFiberAmount(),
                $food->getFiberUnit(),
                $food->getUser()->getId(),
                $food->getQuantity(),
            );
            $rows[] = implode(',',$data);
        }
        $content = implode("\n",$rows);
        $response = new Response($content);
        $response->headers->set("Content-Type",'text/csv');
        $response->headers->set("Content-Disposition",'attachment; filename="foods.csv"');

        return $response;
    }
}
