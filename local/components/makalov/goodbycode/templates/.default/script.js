BX.ready(function () {

    /*
    Вешаем обработчики на событие клик для кнопок добавить и удалить
     */

    document.querySelector('#add_section')
        .addEventListener('click', addSection)

    document.querySelector('#remove_section')
        .addEventListener('click',removeSection)

    /*
    Функция формирует секцию с полем для ввода, кнопкой поиска, и контейнером для вывода товара
    */

    function addSection() {

        let section = BX.create('div', {

            props: {id: 'section'},

            children: [

                BX.create('div', {

                    props: {id: 'search_container'},

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

                                        BX.create('div', {

                                            props: {id: 'add_to_basket'},

                                            children: [

                                                BX.create('button', {

                                                    props: {id: 'add_to_basket_link'},

                                                    text: "Добавить в корзину",

                                                    events: {
                                                        click: add
                                                    }
                                                }),
                                            ]

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

    /*
    Функция удаляет секцию с полем для ввода, кнопкой поиска, и контейнером для вывода товара
     */

    function removeSection() {
        let section = document.querySelectorAll('#section');
        section[section.length-1].remove();
    }

    /*
    Добавляем секцию при загрузке страницы
     */

    addSection();

    let context;
        // link = document.querySelector('#add_to_basket_link');

    /*
    Функция отправляет запрос на контроллер добавляющий товар в корзину
     */

    function add() {

        BX.ajax.runComponentAction('makalov:goodbycode', 'add', {
            mode: 'class',
            data: {
                id: context.querySelector('#good_container').getAttribute('data_product_id')
            }
        })
            .then(function (response) {
                    BX.onCustomEvent('OnBasketChange');
                },
                function (response) {
                    console.log(response);
                });
    }

    /*
    Функция отправляет запрос на контроллер получающий данные о товаре, и выводит данные в контейнер
     */

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


                    item = JSON.parse(response.data.result);
                    console.log(item)

                    if (item) {

                        if(item['ORIGINAL_PRODUCT_PREVIEW_TEXT']){
                            text = item['ORIGINAL_PRODUCT_PREVIEW_TEXT']
                        }
                        else text = item['ORIGINAL_PRODUCT_DETAIL_TEXT'];

                        message.innerHTML = '';

                        price.innerHTML = `${Math.round(item['PRICE_PRICE'])} ${item['PRICE_CURRENCY']}`;

                        goodContainer.setAttribute('style', 'display: flex');
                        goodContainer.setAttribute('data_product_id', item['ID']);

                        name.innerHTML = item['NAME'];
                        name.setAttribute('href', `/catalog/${item['SECTION_CODE']}/${item['ORIGINAL_PRODUCT_CODE']}/`);

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

})

