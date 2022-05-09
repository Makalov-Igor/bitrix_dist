/**
 * Проверка на орфографию
 */


// объявляем переменные

let keys,selectionText, responce, result, title, content, popup;

// код будет выполнятся по событию нажатия клавиши Ctrl+Enter

document.addEventListener('keyup',async function (e) {


    if(keys == 'Control' && e.key == 'Enter' || keys == 'Enter' && e.key == 'Control'){

        // получаем выделенную область текста

        selectionText = document.getSelection().toString();

        // отправляем запрос в yandex speller

        responce =
            await fetch(`https://speller.yandex.net/services/spellservice.json/checkText?text=${selectionText}`);

        // обрабатываем ответ

        responce = await responce.text();
        result = JSON.parse(responce);

        // формируем содержимое для popup

        if(result.length == 0){
            content =
                `<div class="popup_title">${BX.message('NO_ERRORS')}</div>`;
        }
        else {
            content = `<div class="popup_title">
                        ${BX.message('ERRORS_FOUND')}
                        </div><div class="error_list">`

            result.forEach(element => {

            content += `<span style="color: red;">${element['word']}</span>` +
                `<br>${BX.message('YOU_MEANT')}<br><ul>`;

                element.s.forEach(element => {
                    content += element+"<br>";
                })
            content += '</ul>';
            })
        }

        content += '</div>';

        // создаем popup

        popup = BX.PopupWindowManager.create("popup-message", null, {
            content: content,
            zIndex: 100,
            closeIcon: {
                opacity: 1
            },
            closeByEsc: true,
            autoHide: true,
            draggable: true,
            overlay: {
                backgroundColor: 'black',
                opacity: 500
            },
            events: {
                onPopupClose: function() {
                    BX.PopupWindowManager.getCurrentPopup().destroy();
                }}
        });

        // вызываем popup

        popup.show();

        keys = '';
    }
    else keys = e.key;
})