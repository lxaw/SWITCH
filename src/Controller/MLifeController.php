<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

// food entity
use App\Entity\Food;

// see: https://stackoverflow.com/questions/38317137/symfony2-how-to-call-php-function-from-controller
use App\FoodDatabaseInteraction\Classes\DBSearcher;
use App\FoodDatabaseInteraction\Classes\TemplateLoader;

class MLifeController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em; 
    }
    #[Route('', name: 'MLifeController__index')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }    
    #[Route('about/', name: 'MLifeController__about')]
    public function about(): Response
    {
        return $this->render('base/about.html.twig');
    }    

    #[Route('user/pie_charts/',name:"mLifeController__pie_charts")]
    public function pie_charts(): Response
    {
        return $this->render("pie_charts/index.html.twig");
    }

    // query the names of the database, get the initial search food info and the food image
    #[Route('ajax/queryNames',methods:["GET"],name:"queryNames")]
    public function queryNames(Request $request): Response
    {
        $dbSearcher = new DBSearcher();
        $templateLoader = new TemplateLoader();

        $strQuery = $request->get("strQuery");
        $intOffset = (int)$request->get("intOffset");

        $rootDir = $this->getParameter('kernel.project_dir');

        $strOut = "";

        $arrAllTemplateData = $dbSearcher->arrQueryMenustatNames($strQuery,$intOffset);
        foreach($arrAllTemplateData as $subArr){
            $tempBody = $templateLoader->strTemplateToStr($subArr,$rootDir."/templates/pie_charts/menustat/table_entry.html");
            $strOut = $strOut.$tempBody;
        }

        return new Response($strOut);
    }
    
    #[Route('ajax/queryData',methods:["GET"],name:"queryData")] 
    public function queryData(Request $request): Response
    {
        $dbSearcher = new DBSearcher();
        $templateLoader = new TemplateLoader($this->getParameter('kernel.project_dir'));

        $strId = $request->get("strId");

        // return str
        $arrRet = array();

        $arrTemplateData = $dbSearcher->arrQueryMenustatDetail($strId);
        $strModal = $templateLoader->strPopulateMenustatModal($arrTemplateData);
        $arrRet = array(
            "data_type"=>$arrTemplateData['data_type'],
            'modal'=>$strModal
        );
        return new JsonResponse($arrRet);
    }

    #[Route('ajax/submitFoods',methods:["POST"],name:"submitFoods")]
    public function submitFoods(Request $request): Response
    {
        $strId = $request->get("strId");
        $floatQty =  (float) $request->get("strQty");

        // need serving size

        // get food data
        //
        $dbSearcher = new DBSearcher();
        // You can change this to whatever database you want.
        $arrData = $dbSearcher->arrQueryMenustatDetail($strId);
        $food = new Food();

        // set attributes
        $food->setUser($this->getUser());
        $food->setServingSize((float) $arrData[0]['serving_size']);
        $food->setServingSizeUnit($arrData[0]['serving_size_unit']);
        $food->setFatAmount((float) $arrData[0]['fat_amount']);
        $food->setProteinAmount((float) $arrData[0]['protein_amount']);
        $food->setEnergyAmount((float) $arrData[0]['energy_amount']);
        $food->setPotassiumAmount((float) $arrData[0]['potassium_amount']);
        $food->setCarbAmount((float) $arrData[0]['carb_amount']);
        $food->setFiberAmount((float) $arrData[0]['fiber_amount']);
        $food->setDate(new \DateTime());
        $food->setDataId((int) $strId);
        $food->setRestaurant($arrData['restaurant']);
        $food->setFoodName($arrData['description']);
        $food->setQuantity($floatQty);

        // set total values
        // Food class records the macronutrient values for individual food object (ie, one serving), and also that of the totals
        $food->setTotalEnergyAmount($floatQty * (float)$arrData[0]['energy_amount']);
        $food->setTotalFiberAmount($floatQty * (float)$arrData[0]['fiber_amount']);
        $food->setTotalPotassiumAmount($floatQty * (float)$arrData[0]['potassium_amount']);
        $food->setTotalCarbAmount($floatQty * (float)$arrData[0]['carb_amount']);
        $food->setTotalFatAmount($floatQty * (float)$arrData[0]['fat_amount']);
        $food->setTotalProteinAmount($floatQty * (float)$arrData[0]['protein_amount']);
        
        // set image path
        // This is specific to the Menustat pathings
        // In reality you could likely just use the Nutritionix image pathing
        $strFormattedFoodName = preg_replace('/[\W]/','',$arrData['description']);
        $strFormattedRestName= preg_replace('/[\W]/','',$arrData['restaurant']);
        $food->setImagePath("/images/FoodDatabaseImages/menustat"."/".$strFormattedRestName."/".$strFormattedFoodName.".jpeg");

        $this->em->persist($food);
        $this->em->flush();

        return new JsonResponse("done");
    }
}
