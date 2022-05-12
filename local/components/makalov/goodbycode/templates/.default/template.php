<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div id="component_container" data_id="<?=$arParams['IBLOCK_ID'];?>">
    <a href="javascript:void(0)" id="add_section"><?=Loc::getMessage('ADD_SECTION');?></a>
    <a href="javascript:void(0)" id="remove_section"><?=Loc::getMessage('REMOVE_SECTION');?></a>
</div>