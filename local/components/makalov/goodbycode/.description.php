<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage("GOODBYCODE_COMPONENT_NAME"),
    "DESCRIPTION" => Loc::getMessage("GOODBYCODE_COMPONENT_DESCRIPTION"),
    "PATH" => array(
        "ID" => "my",
        "NAME" => "Makalov"
    ),
);
