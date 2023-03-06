<?php

/*
mLIFE controller
General controller for tasks not directly related to the Food entity
*/

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
    // entity manage
    private $em;

    // ctor for entity manager
    public function __construct(EntityManagerInterface $em){
        $this->em = $em; 
    }

    // index page
    #[Route('', name: 'MLifeController__index')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }    

    // about page
    #[Route('about/', name: 'MLifeController__about')]
    public function about(): Response
    {
        return $this->render('base/about.html.twig');
    }    

    /*
    This page is where users enter their foods and the pie charts update.
    */
    #[Route('user/pie_charts/',name:"mLifeController__pie_charts")]
    public function pie_charts(): Response
    {
        return $this->render("pie_charts/index.html.twig");
    }

    /*
    ****************************************
    The following methods are designed to be used with AJAX.
    Since you guys will be using Nutritionix, you could switch these out for queries for the 
    Nutritionix API.

    Thus much of this code will likely not be incredibly similar, as I am treating it as an API call to
    my own food database.
    ****************************************
    */

    // query the names of the database, get the initial search food info and the food image
    #[Route('ajax/queryNames',methods:["GET"],name:"queryNames")]
    public function queryNames(Request $request): Response
    {
        // create something to search the food db
        // note that this is an object I created that is designed to query my
        // food database.
        $dbSearcher = new DBSearcher();
        // this is an object designed to create html templates based on the food database responses
        $templateLoader = new TemplateLoader();

        // get the query of the food
        $strQuery = $request->get("strQuery");
        // get the current offset
        // the offset tells you how far deep in the db to start from.
        // if the offset it 20, then the food database will search for `strQuery`
        // starting from the 21st entry.
        $intOffset = (int)$request->get("intOffset");

        // root directory of the project, used for image paths
        $rootDir = $this->getParameter('kernel.project_dir');

        // the html to be returned
        // in reality it may be better to use an HttpResponse here.
        $strOut = "";

        $arrAllTemplateData = $dbSearcher->arrQueryMenustatNames($strQuery,$intOffset);
        foreach($arrAllTemplateData as $subArr){
            $tempBody = $templateLoader->strTemplateToStr($subArr,$rootDir."/templates/pie_charts/menustat/table_entry.html");
            $strOut = $strOut.$tempBody;
        }

        return new Response($strOut);
    }
    
    /*
    Query for a specific food's data.
    */
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

    /*
    Submit your food entries.
    */
    #[Route('ajax/submitFoods',methods:["POST"],name:"submitFoods")]
    public function submitFoods(Request $request): Response
    {
        $strId = $request->get("strId");
        $floatQty =  (float) $request->get("strQty");

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

        // Likely a better return type possible.
        return new JsonResponse("done");
    }
}
