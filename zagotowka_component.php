<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости");
?>
<?
//Формирую дату
$lastDay = date('d.m.Y', time() - 86400); //от вчера, то есть -86400 секунд
$current = date("d.m.Y"); //по текущую дату
$nextDay = date('d.m.Y', time() + 86400);

CModule::includeModule('iblock');


$arrFilterCurDate = array();
?>

<?
//Задаю условие фильтрации
if (isset($_GET["filter"])){
	if($_GET["filter"] == 'current'){
		$arrFilterCurDate = array("><DATE_ACTIVE_FROM" => array($current, $nextDay));
	}
	elseif ($_GET["filter"] == 'lastday') {// вчера
		$arrFilterCurDate = $arrFilterTwoDate = Array("><DATE_ACTIVE_FROM" => array($lastDay,$current));
	}
	elseif($_GET["filter"] == 'last'){
		$arrFilterCurDate = array("=<DATE_ACTIVE_FROM" => $current);
	}//предыдущие дни
	
	
	elseif ($_GET["filter"] == 'all') {
		$arrFilterCurDate = array();
	}
}
?>


<?
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
$arSelect = array("NAME","ID","IBLOCK_SECTION_ID","PREVIEW_TEXT","DATE_ACTIVE_FROM");
$arFilter = array("IBLOCK_ID" => $iblockID,"SECTION_ID" => array_keys($result),$arrFilterCurDate);
$arSort = array("IBLOCK_SECTION_ID" => "ASC");
$arGroupBy = false;
$res = CIBlockElement::GetList($arSort,$arFilter,$arGroupBy,false, $arSelect);// Получаю нужные поля, сортирую
while ($ob=$res->GetNext()) {
      $result[$ob['IBLOCK_SECTION_ID']]['ITEMS'][] = $ob;

}
foreach ($result as $arFullMess => $Mess) {
  echo "<h2>{$Mess["ID"]}.{$Mess["NAME"]}</h2>";
  foreach ($Mess as $key => $value) {
      $count=1;
      foreach($value as $k => $v){
        echo "<h3>{$Mess['ID']}.{$count}{$v['NAME']}</h3></br>";
        echo TruncateText($v['PREVIEW_TEXT'],200);
        $count++;
      }
  }
}

?>

<p>
<a href="<?=$arResult["SECTION_PAGE_URL"]?>?filter=last">
|Предыдущие дни
</a>
<a href="<?=$arResult["SECTION_PAGE_URL"]?>?filter=lastday">
|Вчера
</a>
<a href="<?=$arResult["SECTION_PAGE_URL"]?>?filter=current">
|Сегодня
</a>
<a href="<?=$arResult["SECTION_PAGE_URL"]?>?filter=all">
|Все
</a>
</p>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
