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

  

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?header( 'Content-Type: text/html; charset=utf-8' );?>
<div class="news-list">
 
<?
CModule::includeModule('iblock');
//Получаю список секций

$iblockID = 2;
$arFilterSect = array("IBLOCK_ID" => $iblockID, "ACTIVE"=>"Y","DEPTH_LEVEL" => 1);
$arSelectSect = array("ID","NAME","DEPTH_LEVEL","IBLOCK_ID");
$arSelectRow = array("ID","NAME");
$result = array();
$arListSec = CIBlockSection::GetList(false,$arFilter,false,$arSelectRow);
while($arListSect = $arListSec->Fetch())
{
  $result[$arListSect['ID']] = $arListSect;
}      
$arSelect = array("NAME","ID","IBLOCK_SECTION_ID","PREVIEW_TEXT");
$arFilter = array("IBLOCK_ID" => $iblockID,"SECTION_ID" => array_keys($result));
$arSort = array("IBLOCK_SECTION_ID" => "ASC");
$arGroupBy = false;
$res = CIBlockElement::GetList($arSort,$arFilter,$arGroupBy,false, $arSelect);// Получаю нужные поля, сортирую
while ($ob=$res->GetNext()) {
      $result[$ob['IBLOCK_SECTION_ID']]['ITEMS'][] = $ob;

}
foreach ($result as $arFullMess => $Mess) {
	echo "<br><h2>{$Mess["ID"]}.{$Mess["NAME"]}</h2></br>";
  foreach ($Mess as $key => $value) {
  		$count=1;
      foreach($value as $k => $v){
      	echo "<br><h3>{$Mess['ID']}.{$count}{$v['NAME']}</h3></br>{$v['PREVIEW_TEXT']}";
      	$count++;
      }
  }
}

//}
//echo "<pre>";
//print_r($result);
//echo "</pre>";
?>

<hr>
</div>
