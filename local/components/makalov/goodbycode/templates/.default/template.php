<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div id="component_container" data_id="<?=$arParams['IBLOCK_ID'];?>">
    <a href="javascript:void(0)" id="add_section"><?=Loc::getMessage('ADD_SECTION');?></a>
    <a href="javascript:void(0)" id="remove_section"><?=Loc::getMessage('REMOVE_SECTION');?></a>
</div>

<script>
    BX.message({IN_BASKET:'<?=Bitrix\Main\Localization\Loc::getMessage("IN_BASKET")?>'});
    BX.message({GOOD_CODE:'<?=Bitrix\Main\Localization\Loc::getMessage("GOOD_CODE")?>'});
    BX.message({SEARCH_ITEM:'<?=Bitrix\Main\Localization\Loc::getMessage("SEARCH_ITEM")?>'});
    BX.message({ADD_TO_BASKET:'<?=Bitrix\Main\Localization\Loc::getMessage("ADD_TO_BASKET")?>'});
    BX.message({DESCRIPTION:'<?=Bitrix\Main\Localization\Loc::getMessage("DESCRIPTION")?>'});
    BX.message({DONT_FOUND:'<?=Bitrix\Main\Localization\Loc::getMessage("DONT_FOUND")?>'});
</script>