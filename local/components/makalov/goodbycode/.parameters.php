<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_ID" => Array(
            "NAME" => Loc::getMessage('CATALOG_IBLOCK_ID'),
            "TYPE" => "STRING",
            "PARENT" => "BASE",
        ),
    )
);