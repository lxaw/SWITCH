<?php

namespace App\FoodDatabaseInteraction\Classes;
use App\FoodDatabaseInteraction\Classes\MySQLiConnection;
use App\FoodDatabaseInteraction\Classes\Util;
use App\FoodDatabaseInteraction\Configs\DatabaseConfig;
// for connection to db
//
// require_once('MySQLiConnection.php');
// for str replace if null
//
// require_once('../funcs/str_replace_if_null.php');

// for get img path 
//
// require_once('../funcs/str_get_img_path.php');

class DBSearcher{
    // for connection to mysql
    //
    private $_MySQLiConnection;

    function __construct(){
        // upon construction create a mysql connection
        $this->_MySQLiConnection = new MySQLiConnection();
    }

    // Queries the menustat db.
    // This function is designed to act when the user 
    // has already queried the db for the name of the food.
    // Thus this is just for getting more info.
    // Inputs:
    // str for food name, str for restaurant name
    // Outputs:
    // array of data on the food
    function arrQueryMenustatDetail($strId){
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                menustat
            where
                menustat_id = ?
        ');
        $stmt->bind_param("i",$strId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // populate all of the data that doesn't change between entries
        //
        $templateData = array(
            'data_type'=>DatabaseConfig::$DATA_TYPE_MENUSTAT,
            'id'=>$strId,
            'description'=>Util::strReplaceIfNull($data[0]['description'],DatabaseConfig::$NULL_REPLACEMENT),
            'restaurant'=>Util::strReplaceIfNull($data[0]['restaurant'],DatabaseConfig::$NULL_REPLACEMENT),
            'img_src' => Util::strGetImgPath($data[0]['description'],$data[0]['restaurant'],DatabaseConfig::$IMG_DIR.'/'.DatabaseConfig::$MENUSTAT_IMGS),
        );
        // populate the data that does change between entries
        //
        foreach($data as $tableEntry){
            $arrSubEntry = array(
                'serving_size'=>Util::strReplaceIfNull($tableEntry['serving_size'],DatabaseConfig::$NULL_REPLACEMENT),
                'serving_size_unit'=>Util::strReplaceIfNull($tableEntry['serving_size_unit'],DatabaseConfig::$NULL_REPLACEMENT),
                'serving_size_text'=>Util::strReplaceIfNull($tableEntry['serving_size_text'],DatabaseConfig::$NULL_REPLACEMENT),
                'protein_amount'=>Util::strReplaceIfNull($tableEntry['protein_amount'],DatabaseConfig::$NULL_REPLACEMENT),
                'energy_amount'=>Util::strReplaceIfNull($tableEntry['energy_amount'],DatabaseConfig::$NULL_REPLACEMENT),
                'fat_amount'=>Util::strReplaceIfNull($tableEntry['fat_amount'],DatabaseConfig::$NULL_REPLACEMENT),
                'carb_amount'=>Util::strReplaceIfNull($tableEntry['carb_amount'],DatabaseConfig::$NULL_REPLACEMENT),
                'potassium_amount'=>Util::strReplaceIfNull($tableEntry['potassium_amount'],DatabaseConfig::$NULL_REPLACEMENT),
                'fiber_amount'=>Util::strReplaceIfNull($tableEntry['fiber_amount'],DatabaseConfig::$NULL_REPLACEMENT)
            );
            // push template data
            //
            array_push($templateData,$arrSubEntry);
        }

        $stmt->close();
        return $templateData;
    }

    // queries the menustat db for names 
    // Input:
    // str name of food
    // output
    // array of food names with their respective restaurants
    function arrQueryMenustatNames($strQuery,$intOffset){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

        // prepare sql statement
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                menustat_query
            where
                description like
                    ?
            limit
                '.DatabaseConfig::$ENTRIES_PER_PAGE.'
            offset
                ?
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intOffset);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $strRestaurant = "";
        $strDescription = "";
        $strImgPath = "";
        $strId = '';

        // start the index off at the offset value
        $intIndex = $intOffset;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strRestaurant = Util::strReplaceIfNull($subArr['restaurant'],DatabaseConfig::$NULL_REPLACEMENT);
            $strDescription= Util::strReplaceIfNull($subArr['description'],DatabaseConfig::$NULL_REPLACEMENT);
            $strImgPath = Util::strGetImgPath($strDescription,$strRestaurant,DatabaseConfig::$IMG_DIR.'/'.DatabaseConfig::$MENUSTAT_IMGS);
            $strId = Util::strReplaceIfNull($subArr['menustat_id'],DatabaseConfig::$NULL_REPLACEMENT);

            $templateData = array(
                "index" =>$intIndex,
                "restaurant" => $strRestaurant,
                "description" => $strDescription,
                "img_path"=>$strImgPath,
                'id'=>$strId,
            );
            array_push($arrAllTemplateData,$templateData);
            $intIndex += 1;
        }

        $stmt->close();

        return $arrAllTemplateData;
    }
}