<?php

/*
Controll all the food-related views.
*/

namespace App\Controller;

// required installs
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FoodFormType;

// food entities and repositorie containers
use App\Entity\Food;
use App\Repository\FoodRepository;

use Symfony\Component\HttpFoundation\Request;

/*
TO DO:
Make sure that private routes are private for all users, ie
for displaying a food by id, make sure only that user who
created the food can do this.
*/

class FoodController extends AbstractController
{
    // The entity manager
    private $em;

    // Constructor
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    // Sort by dates.
    //
    function date_sort($objA,$objB){
        if($objA->getDate() == $objB->getDate()) return 0;
        return ($objA->getDate() < $objB->getDate()) ? -1:1;
    }

    #[Route('/food/date/{date}/',name:'FoodController__food-by-date')]    
    /**
     * byDate
     * Show foods by date.
     * Get all the foods and display them to user.
     * 
     * @param  mixed $date
     * @return Response
     */
    public function byDate($date): Response
    {
        // get each repo
        $foodRepo= $this->em->getRepository(Food::class);

        $userFoods = array();

        // push the foods
        $userFoods = array_merge($userFoods,$foodRepo->findBy([
            'User' =>  $this->getUser()
        ]));

        $foods = array();

        // get the foods by specified date
        foreach($userFoods as $food){
            if($food->getDate()->format('Y-m-d') == $date){
                array_push($foods,$food);
            }
        }

        return $this->render('food/date/index.html.twig',[
            'foods'=>$foods,
            'date'=>$date
        ]);
    }

    #[Route('/food', name: 'FoodController__index')]    
    /**
     * index
     * Display a list of dates where food was entered
     * @return Response
     */
    public function index(): Response
    {
        $conn = $this->em->getConnection();
        $sqlFoodDates = "
        select distinct substring_index(date,' ',1) as subDate
        from (
            select date from food
        ) tableName
        order by subDate DESC
        ";
        $stmt = $conn->prepare($sqlFoodDates);
        $dates = $stmt ->execute()->fetchAll(\PDO::FETCH_COLUMN);

        return $this->render('food/index.html.twig', [
            'dates'=>$dates
        ]);
    }

    #[Route('/food/show/{id}', methods: ["GET"],name:'FoodController__getFoodById')]    
    /**
     * Show a specific food.
     * Given an id and datatype, show the food.
     *
     * @param  mixed $strDataType
     * @param  mixed $id
     * @return Response
     */
    public function show($id): Response
    {
        $food = NULL;
        $menustatRepo = $this->em->getRepository(Food::class);
        $food= $menustatRepo->find($id);

        return $this->render('food/menustat/show.html.twig',[
            'food' => $food
        ]);
    }
    #[Route('/food/update/{id}', methods: ["GET","POST"],name:'FoodController__updateFoodById')]    
    /**
     * Show a specific food.
     * Given an id and datatype, show the food.
     *
     * @param  mixed $strDataType
     * @param  mixed $id
     * @return Response
     */
    public function update($id,Request $request): Response
    {
        $form = NULL;
        $foodRepo = NULL;
        $food = NULL;
        // path to render
        $strRenderPath = "/food/update.html.twig";

        $foodRepo = $this->em->getRepository(Food::class);
        $food = $foodRepo->find($id);
        $form = $this->createForm(FoodFormType::class,$food);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // update all attributes
            //
            $food->setFoodName($form->get('FoodName')->getData());
            $food->setRestaurant($form->get('Restaurant')->getData());
            $food->setFoodCategory($form->get('FoodCategory')->getData());
            $food->setServingSize($form->get('ServingSize')->getData());
            $food->setServingSizeUnit($form->get('ServingSizeUnit')->getData());
            $food->setEnergyAmount($form->get('EnergyAmount')->getData());
            $food->setEnergyUnit($form->get('EnergyUnit')->getData());
            $food->setFatAmount($form->get('FatAmount')->getData());
            $food->setFatUnit($form->get('FatUnit')->getData());
            $food->setCarbAmount($form->get('CarbAmount')->getData());
            $food->setCarbUnit($form->get('CarbUnit')->getData());
            $food->setProteinAmount($form->get('ProteinAmount')->getData());
            $food->setProteinUnit($form->get('ProteinUnit')->getData());
            $food->setQuantity($form->get('Quantity')->getData());
            $food->setPotassiumAmount($form->get('PotassiumAmount')->getData());
            $food->setPotassiumUnit($form->get('PotassiumUnit')->getData());
            $food->setFiberAmount($form->get('FiberAmount')->getData());
            $food->setFiberUnit($form->get('FiberUnit')->getData());
            $food->setDate($form->get('Date')->getData());

            // now set totals
            $food->setTotalEnergyAmount($form->get('EnergyAmount')->getData() * $form->get('Quantity')->getData());
            $food->setTotalFiberAmount($form->get('FiberAmount')->getData() * $form->get('Quantity')->getData());
            $food->setTotalPotassiumAmount($form->get('PotassiumAmount')->getData() * $form->get('Quantity')->getData());
            $food->setTotalFatAmount($form->get('FatAmount')->getData() * $form->get('Quantity')->getData());
            $food->setTotalCarbAmount($form->get('CarbAmount')->getData() * $form->get('Quantity')->getData());
            $food->setTotalProteinAmount($form->get('ProteinAmount')->getData() * $form->get('Quantity')->getData());


            $this->em->flush();
            return $this->redirectToRoute('FoodController__getFoodById',array(
                'id' => $id
            ));
        }

        return $this->render($strRenderPath,[
            'food' => $food,
            'form'=> $form->createView()
        ]);
    }
    private function checkUserOwnsFood(){
        // todo
        
    }
}
