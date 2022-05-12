<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class GoodsByCode extends CBitrixComponent implements Controllerable {

    private function checkModules()
    {
        if ( !Loader::includeModule('iblock') && !Loader::includeModule('catalog'))
        {
            throw new \Exception(Loc::getMessage('GOODBYCODE_COMPONENT_PATH_NAME'));
        }

        Loader::includeModule('catalog');

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

    public function getAction(string $code, $iblockId){

        $this->checkModules();

        $good = \Bitrix\Iblock\ElementTable::query()

            ->setSelect([
                '*',
                'SECTION_CODE'=>'section.CODE',
                'IBLOCK_CODE'=>'iblock.CODE',
                 "BASE_PRICE"=>"price.PRICE",
                 "CURRENCY" => "price.CURRENCY"
            ])
            ->registerRuntimeField('section',[
                'data_type' =>  'Bitrix\Iblock\SectionTable',
                'reference' => array('=this.IBLOCK_SECTION_ID' => 'ref.ID'),
            ])
            ->registerRuntimeField('price',[
                'data_type' =>  '\Bitrix\Catalog\PriceTable',
                'reference' => array('=this.ID' => 'ref.PRODUCT_ID'),
            ])
            ->where(['IBLOCK_ID', $iblockId])
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

//        $good = \Bitrix\Iblock\ElementTable::query()
//            ->setSelect([
//                '*',
//                'SECTION_CODE'=>'section.CODE',
//                'IBLOCK_CODE'=>'iblock.CODE',
//                "BASE_PRICE"=>"price.PRICE"
//            ])
//            ->registerRuntimeField('section',[
//                'data_type' =>  'Bitrix\Iblock\SectionTable',
//                'reference' => array('=this.IBLOCK_SECTION_ID' => 'ref.ID'),
//            ])
//            ->registerRuntimeField('price',[
//                'data_type' =>  '\Bitrix\Catalog\PriceTable',
//                'reference' => array('=this.ID' => 'ref.PRODUCT_ID'),
//            ])
//            ->setFilter(['IBLOCK_ID'=> 3])
//                ->where('XML_ID', 232);
//
//        $result = $good->fetch();

        \Bitrix\Main\Diag\Debug::dump($result);

        }

}