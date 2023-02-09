<?php
/*

*/

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    // id of food object
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Date when food object created
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $Date = null;

    // Name of food object
    #[ORM\Column(type: Types::TEXT)]
    private ?string $FoodName;

    // Restaurant of food, if present
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Restaurant = null;

    // Food category (breakfast, lunch, dinner, snack, etc)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $FoodCategory = null;

    // Serving size of the food
    #[ORM\Column(nullable: true)]
    private ?float $ServingSize = null;

    // Serving size unit of food
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ServingSizeUnit = null;

    // Amount of energy of the food (ie, in kcal)
    #[ORM\Column(nullable: true)]
    private ?float $EnergyAmount = null;

    // Unit for energy of food (ie, kcal)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $EnergyUnit = null;

    // Fat amount of food
    #[ORM\Column(nullable: true)]
    private ?float $FatAmount = null;

    // Fat unit of food (ie, gram)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $FatUnit= null;

    // Carb amount of food
    #[ORM\Column(nullable: true)]
    private ?float $CarbAmount = null;

    // Carb unit of food (ie, gram)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $CarbUnit = null;

    // Protein amount of food
    #[ORM\Column(nullable: true)]
    private ?float $ProteinAmount = null;

    // Protein unit of food (ie, gram)
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ProteinUnit = null;

    // User who saved food
    #[ORM\ManyToOne(inversedBy: 'food', cascade: ['persist'])]
    private ?User $User = null;

    // Quantity of food (ie, at 1.5 1gram servings of yogurt)
    #[ORM\Column(nullable:true)]
    private ?float $Quantity = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $DataType = null;

    // Data id (the ID of the food from the database we are pulling from.
    // For instance, this would be the id of the food from the Menustat database,
    // if we were to use that.)
    #[ORM\Column(nullable:true)]
    private ?int $DataId = null;

    #[ORM\Column(nullable: true)]
    private ?float $PotassiumAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PotassiumUnit = null;

    #[ORM\Column(nullable: true)]
    private ?float $FiberAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FiberUnit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ImagePath = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalEnergyAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalFiberAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalPotassiumAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalFatAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalCarbAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $TotalProteinAmount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoodName(): ?string
    {
        return $this->FoodName;
    }

    public function setFoodName(string $name): self
    {
        $this->FoodName = $name;

        return $this;
    }

    public function getRestaurant(): ?string
    {
        return $this->Restaurant;
    }

    public function setRestaurant(?string $Restaurant): self
    {
        $this->Restaurant = $Restaurant;

        return $this;
    }

    public function getFoodCategory(): ?string
    {
        return $this->FoodCategory;
    }

    public function setFoodCategory(?string $FoodCategory): self
    {
        $this->FoodCategory = $FoodCategory;

        return $this;
    }

    public function getServingSize(): ?float
    {
        return $this->ServingSize;
    }

    public function setServingSize(?float $ServingSize): self
    {
        $this->ServingSize = $ServingSize;

        return $this;
    }

    public function getServingSizeUnit(): ?string
    {
        return $this->ServingSizeUnit;
    }

    public function setServingSizeUnit(?string $ServingSizeUnit): self
    {
        $this->ServingSizeUnit = $ServingSizeUnit;

        return $this;
    }

    public function getEnergyAmount(): ?float
    {
        return $this->EnergyAmount;
    }

    public function setEnergyAmount(?float $EnergyAmount): self
    {
        $this->EnergyAmount = $EnergyAmount;

        return $this;
    }

    public function getEnergyUnit(): ?string
    {
        return $this->EnergyUnit;
    }

    public function setEnergyUnit(?string $EnergyUnit): self
    {
        $this->EnergyUnit = $EnergyUnit;

        return $this;
    }

    public function getFatAmount(): ?float
    {
        return $this->FatAmount;
    }

    public function setFatAmount(?float $FatAmount): self
    {
        $this->FatAmount = $FatAmount;

        return $this;
    }

    public function getFatUnit(): ?string
    {
        return $this->FatUnit;
    }

    public function setFatUnit(?string $FatUnit): self
    {
        $this->FatUnit = $FatUnit;

        return $this;
    }

    public function getCarbAmount(): ?float
    {
        return $this->CarbAmount;
    }

    public function setCarbAmount(?float $CarbAmount): self
    {
        $this->CarbAmount = $CarbAmount;

        return $this;
    }

    public function getCarbUnit(): ?string
    {
        return $this->CarbUnit;
    }

    public function setCarbUnit(?string $CarbUnit): self
    {
        $this->CarbUnit = $CarbUnit;

        return $this;
    }

    public function getProteinAmount(): ?float
    {
        return $this->ProteinAmount;
    }

    public function setProteinAmount(?float $ProteinAmount): self
    {
        $this->ProteinAmount = $ProteinAmount;

        return $this;
    }

    public function getProteinUnit(): ?string
    {
        return $this->ProteinUnit;
    }

    public function setProteinUnit(?string $ProteinUnit): self
    {
        $this->ProteinUnit = $ProteinUnit;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->Quantity;
    }

    public function setQuantity(float $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getDataType(): ?string
    {
        return $this->DataType;
    }

    public function setDataType(string $DataType): self
    {
        $this->DataType = $DataType;

        return $this;
    }

    public function getDataId(): ?int
    {
        return $this->DataId;
    }

    public function setDataId(int $DataId): self
    {
        $this->DataId = $DataId;

        return $this;
    }
    public function getDate(): ?\DateTime
    {
        return $this->Date;
    }
    public function setDate(?\DateTime $Date): self
    {
        $this->Date = $Date;
        return $this;
    }

    public function getPotassiumAmount(): ?float
    {
        return $this->PotassiumAmount;
    }

    public function setPotassiumAmount(?float $PotassiumAmount): self
    {
        $this->PotassiumAmount = $PotassiumAmount;

        return $this;
    }

    public function getPotassiumUnit(): ?string
    {
        return $this->PotassiumUnit;
    }

    public function setPotassiumUnit(?string $PotassiumUnit): self
    {
        $this->PotassiumUnit = $PotassiumUnit;

        return $this;
    }

    public function getFiberAmount(): ?float
    {
        return $this->FiberAmount;
    }

    public function setFiberAmount(?float $FiberAmount): self
    {
        $this->FiberAmount = $FiberAmount;

        return $this;
    }

    public function getFiberUnit(): ?string
    {
        return $this->FiberUnit;
    }

    public function setFiberUnit(?string $FiberUnit): self
    {
        $this->FiberUnit = $FiberUnit;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->ImagePath;
    }

    public function setImagePath(?string $ImagePath): self
    {
        $this->ImagePath = $ImagePath;

        return $this;
    }

    public function getTotalEnergyAmount(): ?float
    {
        return $this->TotalEnergyAmount;
    }

    public function setTotalEnergyAmount(?float $TotalEnergyAmount): self
    {
        $this->TotalEnergyAmount = $TotalEnergyAmount;

        return $this;
    }

    public function getTotalFiberAmount(): ?float
    {
        return $this->TotalFiberAmount;
    }

    public function setTotalFiberAmount(?float $TotalFiberAmount): self
    {
        $this->TotalFiberAmount = $TotalFiberAmount;

        return $this;
    }

    public function getTotalPotassiumAmount(): ?float
    {
        return $this->TotalPotassiumAmount;
    }

    public function setTotalPotassiumAmount(?float $TotalPotassiumAmount): self
    {
        $this->TotalPotassiumAmount = $TotalPotassiumAmount;

        return $this;
    }

    public function getTotalFatAmount(): ?float
    {
        return $this->TotalFatAmount;
    }

    public function setTotalFatAmount(?float $TotalFatAmount): self
    {
        $this->TotalFatAmount = $TotalFatAmount;

        return $this;
    }

    public function getTotalCarbAmount(): ?float
    {
        return $this->TotalCarbAmount;
    }

    public function setTotalCarbAmount(?float $TotalCarbAmount): self
    {
        $this->TotalCarbAmount = $TotalCarbAmount;

        return $this;
    }

    public function getTotalProteinAmount(): ?float
    {
        return $this->TotalProteinAmount;
    }

    public function setTotalProteinAmount(?float $TotalProteinAmount): self
    {
        $this->TotalProteinAmount = $TotalProteinAmount;

        return $this;
    }
}
