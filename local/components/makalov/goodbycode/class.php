<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Contract\Controllerable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class GoodsByCode extends CBitrixComponent implements Controllerable {

    /**
     * Подключем модули
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     */

    private function checkModules()
    {
        if ( !Loader::includeModule('iblock') && !Loader::includeModule('catalog'))
        {
            throw new \Exception(Loc::getMessage('GOODBYCODE_COMPONENT_PATH_NAME'));
        }
        Loader::includeModule('iblock');
        Loader::includeModule('catalog');

        return true;
    }

    /**
     * Выключаем префильтры
     * @return array[][]
     */

    public function configureActions(): array
    {
        return [
            'get' => [
                'prefilters'=> []
            ],
            'add' => [
                'prefilters'=>[]
            ]
        ];
    }

    /**
     *
     * Метод получает данные о товаре (торговом предложении)
     *
     * @param string $code
     * @param $iblockId
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */

    public function getAction(string $code, $iblockId){

        $this->checkModules();

        $good = \Bitrix\Iblock\Elements\ElementOffersTable::query()
            ->setSelect([
                '*',
                'SECTION_CODE'=>'section.CODE',
                'IBLOCK_CODE'=>'iblock.CODE',
                "PRICE_"=>"price",
                "CML2_VALUE"=>"CML2_LINK.VALUE",
                "ORIGINAL_PRODUCT_" => "product",
            ])
            ->registerRuntimeField('product',[
                'data_type' =>  '\Bitrix\Iblock\Elements\ElementCatalogTable',
                'reference' => array('=this.CML2_VALUE' => 'ref.ID'),
            ])
            ->registerRuntimeField('section',[
                'data_type' =>  'Bitrix\Iblock\SectionTable',
                'reference' => array('=this.ORIGINAL_PRODUCT_IBLOCK_SECTION_ID' => 'ref.ID'),
            ])
            ->registerRuntimeField('price',[
                'data_type' =>  '\Bitrix\Catalog\PriceTable',
                'reference' => array('=this.ID' => 'ref.PRODUCT_ID'),
            ])
            ->where(['IBLOCK_ID'=> $iblockId])
            ->where('XML_ID', $code);

        $result = $good->fetch();

        if(!empty($result['DETAIL_PICTURE'])){
            $result['DETAIL_PICTURE'] = CFile::GetPath($result['DETAIL_PICTURE']);
        }

        return [
            'result' => json_encode($result)
        ];
    }

    /**
     *
     * Метод добавляет товар в корзину
     *
     * @param $id
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */

    public function addAction($id){

        $this->checkModules();


        $fields = [
            'PRODUCT_ID' => $id,
            'QUANTITY' => 1,
        ];

        $res = \Bitrix\Catalog\Product\Basket::addProduct($fields);
        return $res->isSuccess();
    }

    public function ExecuteComponent () {

        $this->includeComponentTemplate();

        }

}