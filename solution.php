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
CModule::includeModule('iblock');
//Получаю список секций
$arFields = array();
$iblockID = 2;
$arFilterSect = array("IBLOCK_ID" => $iblockID, "ACTIVE"=>"Y","SECTION_ID" => "");
$arSelectSect = array("ID","NAME");

$res1 = CIBlockSection::GetList(Array(),$arFilterSect,false,$arSelectSect,false);
while ($ob1=$res1->GetNextElement()) {
	$arFields1[] = $ob1->GetFields();
}

foreach ($arFields1 as $arr => $prop) {
$count=1;
echo "<h2>"."<br>".$prop["ID"].".".$prop["NAME"]."</br>"."</h2>";
//Получаю список элементов
$arSelect = array("NAME","ID","IBLOCK_SECTION_ID","PREVIEW_TEXT");
$arFilter = array("IBLOCK_ID" => $iblockID);
$res = CIBlockElement::GetList(array(),$arFilter,false,false, $arSelect);// Получаю нужные поля, сортирую
	while ($ob=$res->GetNextElement()) {
		$row[] = $ob->GetFields();
			echo "<h3>"."<br>".$prop["ID"].".{$count} ".$row["NAME"]."<br/>"."</h3>".TruncateText($row["PREVIEW_TEXT"],200); 
			$count++;
		}	

}

/*for($d=0;$d<=count($arFields1);$d++){
	$arCount = 0;
	$arHead = $arFields1["$d"]["ID"];
	echo "<h2>"."</br>".$arFields1["$d"]["ID"] . "  ". $arFields1["$d"]["NAME"]."</h2>" . "</br>";
	for($i=0;$i<=count($arFields_res);$i++){
		if($arFields1["$d"]["ID"] === $arFields_res["$i"]["IBLOCK_SECTION_ID"]){
				$arCount++;
				echo "</br>"."<h3>"."$arHead."."$arCount"."  ". $arFields_res["$i"]["NAME"]."</h3>" ."</br>" . $arFields_res["$i"]["PREVIEW_TEXT"] . "</br>";
		}	
	}
}*/
?>


<hr>
</div>
