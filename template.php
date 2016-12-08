<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
<?
function MakeElementsTree($prntIBid,$lnkPropName,$eltype = null){
    if(!CModule::IncludeModule("iblock")){
    echo "не подключается модуль инфоблоки";
    }
    // если номер раздела передан в функцию, добавляем его в фильтр
        if (isset($eltype) && $eltype != '')
           {
            $arFilter=array(
                "IBLOCK_ID" => $prntIBid,
                "SECTION_ID" => $eltype,
                "ACTIVE" => "Y",
                );
         }
                  else{
          // если нет выбираем разделы верхнего уровня
        $arFilter=array(
            "IBLOCK_ID" => $prntIBid,
            "DEPTH_LEVEL" => 1,
            "ACTIVE" => "Y",
            );
         }
       $ar_result=Array();
      // ВЫБИРАЕМ ЭЛЕМЕНТЫ ИЗ КОРНЕВОГО РАЗДЕЛА
        $arFilterRoot = array(
            "IBLOCK_ID" => $prntIBid,
            "SECTION_ID" => "",
            "ACTIVE" => "Y",
            );
    $arProjRootElem = CIBlockElement::GetList(array("SORT"=>"ASC"),$arFilterRoot,false);
    while($projResElem = $arProjRootElem->GetNextElement()){
        $arElemFld = $projResElem->GetFields();
        $arElemProp = $projResElem->GetProperties();
        $arSelPropRoot[$arElemFld["ID"]] = $arElemProp[$lnkPropName]["VALUE"];
     }
    foreach($arSelPropRoot as $propkey => $propval){
        //если значение свойства где хранится привязка множественное - формируем ключ и объединяем значения в одну строку
               if(count($propval) > 1){
                    $rpppval = "";
        $rpppres = "";
        foreach($propval as $rppkey => $rppval){
                    $arLnkRootElem = CIBlockElement::GetByID($rppval);
            if($arLnkRootElem_res = $arLnkRootElem->GetNext()){
                $rpppval .= $rppval;
                $rpppres .= $arLnkRootElem_res["NAME"]." ";
            }
        }
        $ar_result_root["CUST_ITEMS"][$rpppval]["NAME"] = $rpppres;
        $arElemVal = CIBlockElement::GetByID($propkey);
        if($arLnkRootElem_res = $arElemVal->GetNext()){
             $ar_result_root["CUST_ITEMS"][$rpppval]["ELEM"][$arLnkRootElem_res["ID"]]["NAME"]= $arLnkRootElem_res["NAME"];
                 $ar_result_root["CUST_ITEMS"][$rpppval]["ELEM"][$arLnkRootElem_res["ID"]]["PREVIEW_TEXT"] = $arLnkRootElem_res["PREVIEW_TEXT"];
                 $ar_result_root["CUST_ITEMS"][$rpppval]["ELEM"][$arLnkRootElem_res["ID"]]["DETAIL_TEXT"] = $arLnkRootElem_res["DETAIL_TEXT"];
                 $ar_result_root["CUST_ITEMS"][$rpppval]["ELEM"][$arLnkRootElem_res["ID"]]["D_TXT_LENGTH"] = strlen($arRootLnkElem_res["DETAIL_TEXT"]);
                 $ar_result_root["CUST_ITEMS"][$rpppval]["ELEM"][$arLnkRootElem_res["ID"]]["DETAIL_PAGE_URL"] = $arRootLnkElem_res["DETAIL_PAGE_URL"];
        }
       }
    // иначе просто получаем ключ и значение.
    else{
        foreach($propval as $rppkey => $rppval){
        $arLnkRootElem = CIBlockElement::GetByID($rppval);
        if($arLnkRootElem_res = $arLnkRootElem->GetNext()){
              $ar_result_root["CUST_ITEMS"][$rppval]["NAME"] = $arLnkRootElem_res["NAME"];                              
        }
     }
    $arElemVal = CIBlockElement::GetByID($propkey);
    if($arLnkRootElem_res = $arElemVal->GetNext()){
        $ar_result_root["CUST_ITEMS"][$rppval]["ELEM"][$arLnkRootElem_res["ID"]]["NAME"]= $arLnkRootElem_res["NAME"];
            $ar_result_root["CUST_ITEMS"][$rppval]["ELEM"][$arLnkRootElem_res["ID"]]["PREVIEW_TEXT"] = $arLnkRootElem_res["PREVIEW_TEXT"];
            $ar_result_root["CUST_ITEMS"][$rppval]["ELEM"][$arLnkRootElem_res["ID"]]["DETAIL_TEXT"] = $arLnkRootElem_res["DETAIL_TEXT"];
            $ar_result_root["CUST_ITEMS"][$rppval]["ELEM"][$arLnkRootElem_res["ID"]]["D_TXT_LENGTH"] = strlen($arRootLnkElem_res["DETAIL_TEXT"]);
            $ar_result_root["CUST_ITEMS"][$rppval]["ELEM"][$arLnkRootElem_res["ID"]]["DETAIL_PAGE_URL"] = $arRootLnkElem_res["DETAIL_PAGE_URL"];
    }
        }
       $ar_result_root["CUST_SUBSECT"]= $arSelPropRoot;
       unset($arSelPropRoot);
    }           
  // ВЫБИРАЕМ ЭЛЕМЕНТЫ ИЗ РАЗДЕЛОВ
$arProj = CIBlockSection::GetList(array("SORT"=>"ASC"),$arFilter,false);
     while($projRes = $arProj->GetNextElement())
       {
    $arFields = $projRes->GetFields();
    $ar_result[$arFields["ID"]]["NAME"] = $arFields["NAME"];
    $ar_result[$arFields["ID"]]["CODE"] = $arFields["CODE"];
      }         
     // узнаем ИД связанных элементов из другого инфоблока
     foreach($ar_result as $arrkey => $arrvalue){
       $arProjElem = CIBlockElement::GetList(array("SORT"=>"ASC"),array("SECTION_ID"=>$arrkey),false);
        while($projResElem = $arProjElem->GetNextElement())
           {
        $arElemFld = $projResElem->GetFields();
        $arElemProp = $projResElem->GetProperties();
        $arSelProp[$arElemFld["ID"]] = $arElemProp[$lnkPropName]["VALUE"];
          }
       foreach($arSelProp as $propkey => $propval){
        //если значение свойства где хранится привязка множественное - формируем ключ и объединяем значения в одну строку
        if(count($propval > 1)){
                 $pppval = "";
                             $pppres = "";
                 foreach($propval as $ppkey => $ppval){
              $arLnkElem = CIBlockElement::GetByID($ppval);
                if($arLnkElem_res = $arLnkElem->GetNext()){
                    $pppval .= $ppval;
                    $pppres .= $arLnkElem_res["NAME"]." ";
                }                            
            }
            $ar_result[$arrkey]["CUST_ITEMS"][$pppval]["NAME"] = $pppres;
            $arElemVal = CIBlockElement::GetByID($propkey);
             if($arLnkElem_res = $arElemVal->GetNext()){
            $ar_result[$arrkey]["CUST_ITEMS"][$pppval]["ELEM"][$arLnkElem_res["ID"]]["NAME"]= $arLnkElem_res["NAME"];
                  $ar_result[$arrkey]["CUST_ITEMS"][$pppval]["ELEM"][$arLnkElem_res["ID"]]["PREVIEW_TEXT"] = $arLnkElem_res["PREVIEW_TEXT"];
                  $ar_result[$arrkey]["CUST_ITEMS"][$pppval]["ELEM"][$arLnkElem_res["ID"]]["DETAIL_TEXT"] = $arLnkElem_res["DETAIL_TEXT"];
                  $ar_result[$arrkey]["CUST_ITEMS"][$pppval]["ELEM"][$arLnkElem_res["ID"]]["D_TXT_LENGTH"] = strlen($arLnkElem_res["DETAIL_TEXT"]);
                  $ar_result[$arrkey]["CUST_ITEMS"][$pppval]["ELEM"][$arLnkElem_res["ID"]]["DETAIL_PAGE_URL"] = $arLnkElem_res["DETAIL_PAGE_URL"];
                 }                          
       }
    // иначе просто получаем ключ и значение.
    else{
        foreach($propval as $ppkey => $ppval){
        $arLnkElem = CIBlockElement::GetByID($ppval);
        if($arLnkElem_res = $arLnkElem->GetNext()){
            $ar_result[$arrkey]["CUST_ITEMS"][$ppval]["NAME"] = $arLnkElem_res["NAME"];
        }                       
    }
    $arElemVal = CIBlockElement::GetByID($propkey);
    if($arLnkElem_res = $arElemVal->GetNext()){
          $ar_result[$arrkey]["CUST_ITEMS"][$ppval]["ELEM"][$arLnkElem_res["ID"]]["NAME"]= $arLnkElem_res["NAME"];
                      $ar_result[$arrkey]["CUST_ITEMS"][$ppval]["ELEM"][$arLnkElem_res["ID"]]["PREVIEW_TEXT"] = $arLnkElem_res["PREVIEW_TEXT"];
                      $ar_result[$arrkey]["CUST_ITEMS"][$ppval]["ELEM"][$arLnkElem_res["ID"]]["DETAIL_TEXT"] = $arLnkElem_res["DETAIL_TEXT"];
                      $ar_result[$arrkey]["CUST_ITEMS"][$ppval]["ELEM"][$arLnkElem_res["ID"]]["D_TXT_LENGTH"] = strlen($arLnkElem_res["DETAIL_TEXT"]);
                      $ar_result[$arrkey]["CUST_ITEMS"][$ppval]["ELEM"][$arLnkElem_res["ID"]]["DETAIL_PAGE_URL"] = $arLnkElem_res["DETAIL_PAGE_URL"];
    }                           
     }
}                   
$ar_result[$arrkey]["CUST_SUBSECT"]= $arSelProp;
unset($arSelProp);
}
// ПОКАЗЫВАЕМ ЭЛЕМЕНТЫ ИЗ КОРНЕВОГО РАЗДЕЛА
    if(isset($ar_result_root) && is_array($ar_result_root["CUST_ITEMS"]) && count($ar_result_root["CUST_ITEMS"]) > 0)
      {
            foreach($ar_result_root ["CUST_ITEMS"] as $arr_cust_items){
        echo "<p class=\"surname2\">".$arr_cust_items["NAME"]."</p>";
        echo "<ul style=\"margin-bottom:10px;\">";
              foreach($arr_cust_items["ELEM"] as $arrItem){
            echo "<li class=\"gvert\" style=\"margin-bottom:10px;\">";
            echo $arrItem["NAME"]; 
                        echo "</li>";
             }
        echo "</ul>";
           }
      }
// ПОКАЗЫВАЕМ ЭЛЕМЕНТЫ ИЗ РАЗДЕЛОВ
    foreach($ar_result as $key => $arrValues)
       {
             if(is_array($arrValues["CUST_ITEMS"]) && count($arrValues["CUST_ITEMS"]) > 0)
               {
        echo "<h3 class=\"title1\" id=\"".$arrValues["CODE"]."\">".$arrValues["NAME"]."</h3>";
            foreach($arrValues["CUST_ITEMS"] as $arr_cust_items){
            echo "<p class=\"surname2\">".$arr_cust_items["NAME"]."</p>";
            echo "<ul style=\"margin-bottom:10px;\">";
               foreach($arr_cust_items["ELEM"] as $arrItem){
                     echo "<li class=\"gvert\" style=\"margin-bottom:10px;\">";
                        echo $arrItem["NAME"]; 
                     echo "</li>";
                  }
                        echo "</ul>";
           }
                }
           } 
  } 

  MakeElementsTree(2);


?>

<hr>
</div>
