$arFilter = array('IBLOCK_ID'=>$IBLOCK_ID, 'ACTIVE'=>'Y');
$rsSect = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("IBLOCK_ID", "ID", "NAME", "SECTION_PAGE_URL"));
while ($arSect = $rsSect->GetNext())
{
$aMenuLinksNew[] = array(
    $arSect["NAME"], 
    $arSect["SECTION_PAGE_URL"], 
    array(), 
    array("SECTION"=>true, "DEPTH_LEVEL"=>2), 
    ""
);
$arSelect = Array("ID", "NAME", "DETAIL_PAGE_URL");
$arFilterElem = Array('IBLOCK_ID'=>$IBLOCK_ID, "SECTION_ID"=>$arSect["ID"], "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilterElem, false, false, $arSelect);
if($arFields = $res->GetNext())
{
    $aMenuLinksNew[] = array(
        $arFields["NAME"], 
        $arFields["DETAIL_PAGE_URL"], 
        array(), 
        array("SECTION"=>false, "DEPTH_LEVEL"=>3), 
        ""
    );
 }
}
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksNew);