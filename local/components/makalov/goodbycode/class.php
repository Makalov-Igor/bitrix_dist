<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class GoodsByCode extends CBitrixComponent implements Controllerable {

    private function checkModules()
    {
        if ( !Loader::includeModule('iblock'))
        {
            throw new \Exception(Loc::getMessage('GOODBYCODE_COMPONENT_PATH_NAME'));
        }

        return true;
    }

    public function configureActions(): array
    {
        return [
            'get' => [
                'prefilters'=> []
            ]
        ];
    }

    public function getAction(string $code){

        $this->checkModules();

        $good = \Bitrix\Iblock\ElementTable::query()
            ->setSelect(['*','SECTION_CODE'=>'section.CODE','IBLOCK_CODE'=>'iblock.CODE'])
            ->registerRuntimeField('section',[
                'data_type' =>  'Bitrix\Iblock\SectionTable',
                'reference' => array('=this.IBLOCK_SECTION_ID' => 'ref.ID'),
            ])
            ->where('XML_ID', $code);

        $result = $good->fetch();

        if(!empty($result['DETAIL_PICTURE'])){
            $result['DETAIL_PICTURE'] = CFile::GetPath($result['DETAIL_PICTURE']);
        }

        return [
            'result' => json_encode($result)
        ];
    }

    public function ExecuteComponent () {

        $this->includeComponentTemplate();

        }

}