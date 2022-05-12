<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div id="component_container" data_id="<?=$arParams['IBLOCK_ID'];?>">
    <a href="javascript:void(0)" onclick="addSection()">Добавить поле</a>
</div>

<script>

    function addSection() {

        let section = BX.create('div', {

            props: {id: 'section'},

            children: [

                BX.create('div', {


                    children: [

                        BX.create('input', {

                            props: {id: 'xml_input', placeholder: 'Код товара'},

                        }),

                        BX.create('button', {

                            props: {id: 'search_goods_button', type: 'submit'},

                            text: 'Найти товар',

                            events: {
                                click: getData,
                            }
                        }),
                    ],
                }),

                BX.create('div', {

                    props: {id: 'good_params'},

                    children: [

                        BX.create('div', {

                            props: {id: 'message'},

                        }),

                        BX.create('div', {

                            props: {id: 'good_container'},

                            children: [

                                BX.create('div', {

                                    props: {id: 'image'},

                                }),

                                BX.create('div', {

                                    props: {id: 'good_info'},

                                    children: [

                                        BX.create('a', {

                                            props: {id: 'name'},

                                        }),

                                        BX.create('div', {

                                            props: {id: 'description'},

                                        }),

                                        BX.create('div', {

                                            props: {id: 'price'},

                                        }),

                                    ]

                                }),
                            ]

                        }),

                    ],

                })

            ]
        })
        BX.append(section, BX('component_container'));
    }

    addSection();

    let context;

    function getData() {

        context = this.parentElement.parentElement;

        let message = context.querySelector('#message'),
            name = context.querySelector('#name'),
            goodContainer = context.querySelector('#good_container'),
            description = context.querySelector('#description'),
            price = context.querySelector('#price'),
            image = context.querySelector('#image'),
            text,item;

        BX.ajax.runComponentAction('makalov:goodbycode', 'get', {
            mode: 'class',
            data: {
                code: context.querySelector('#xml_input').value,
                iblockId: document
                    .querySelector('#component_container')
                    .getAttribute('data_id')
            }
        })
            .then(function (response) {

                    console.log(response.data.result)
                    item = JSON.parse(response.data.result);

                    if (item) {

                        if(item['PREVIEW_TEXT']){
                            text = item['PREVIEW_TEXT']
                        }
                        else text = item['DETAIL_TEXT'];

                        message.innerHTML = '';

                        price.innerHTML = `${Math.round(item['BASE_PRICE'])} ${item['CURRENCY']}`;

                        goodContainer.setAttribute('style', 'display: flex');

                        name.innerHTML = item['NAME'];
                        name.setAttribute('href', `/catalog/${item['SECTION_CODE']}/${item['CODE']}/`);

                        description.innerHTML = `Описание: ${text}`;

                        image.style.background = `url('${item['DETAIL_PICTURE']}')`

                    } else {

                        goodContainer.setAttribute('style', 'display: none');
                        message.innerHTML = 'Товар не найден';

                    }

                },
                function (response) {
                    console.log(response);
                });

    }


</script>