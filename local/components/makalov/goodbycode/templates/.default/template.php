<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div id="component_container">
    <a href="javascript:void(0)" onclick="addSection()">Добавить поле</a
    <div><input type="number" id="xml_input" placeholder="Код товара">
        <button type="submit" id="search_goods_button">Найти товар</button>
    </div>
    <div id="good_params">
        <div id="message"></div>
        <div id="good_container">
            <div id="image"></div>
            <div id="good_info"><a id="name"></a>
                <div id="description">
                </div>
            </div>
        </div>
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
                                    click: getData
                                }

                            }),
                        ]
                    }),

                    BX.create('div', {

                        props: {id: 'good_params'}

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

                                        ]

                                    }),
                                ]

                            }),

                        ],

                    })

                    ]
            }
            BX.append(section, BX('component_container'));
        }
            addSection();

            function getData() {

                console.log('click')
                console.log(this)

                let context = this;
                let item;

                BX.ajax.runComponentAction('makalov:goodbycode', 'get', {
                    mode: 'class',
                    data: {
                        code: BX('xml_input').value
                    }
                }).then(function (response) {
                    item = JSON.parse(response.data.result);

                    if (item) {

                        context.querySelector('message').innerHTML = '';
                        BX('good_container').setAttribute('style', 'display: flex');
                        BX('image').style.background = `url('${item['DETAIL_PICTURE']}')`
                        BX('name').innerHTML = item['NAME'];
                        BX('name').setAttribute('href', `/catalog/${item['SECTION_CODE']}/${item['CODE']}/`);
                        BX('description').innerHTML = `Описание: ${item['PREVIEW_TEXT']}`;
                    } else {
                        BX('good_container').setAttribute('style', 'display: none');
                        BX('message').innerHTML = 'Товар не найден';
                    }

                }, function (response) {
                    console.log(response);
                });

            }


    </script>