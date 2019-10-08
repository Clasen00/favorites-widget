class FavoriteController {

    /**
     * Создает экземпляр FavoriteController
     *
     * @constructor
     * @param {HTML Element} element - Элемент, на дочерние блоки которого навешиваются события
     */
    constructor(element) {
        this.element = element;
        this.parentNode = document.getElementById("menuElements");
        this.pageId = 0;
        this.deleteActionPath = '/new/favorites/favorites/delete?id=';
        this.actionTypeSave = 'save';
        this.actionTypeDelete = 'delete';
        this.footerButton = '';
    }

    /**
     * Обрабатывает клик по кнопке "Добавить эту страницу в избранное".
     *
     * @param {HTML} target - Элемент по которому произошел клик
     */
    async save(target) {
        const pageName = target.getAttribute('data-pagename');
        const pageUrl = target.getAttribute('data-pageurl');
        const pageType = target.getAttribute('data-pagetype');
        const url = "/new/favorites/favorites/create?pageUrl=" + pageUrl + "&pageName=" + pageName + "&pageType=" + pageType;

        this.footerButton = target;

        await this.sendDataToUrl(url, this.actionTypeSave);
    }

    /**
     * Обрабатывает клик иконке "X". Удаляет страницу из базы и из списка избранных страниц.
     *
     * @param {HTML} target - Элемент по которому произошел клик
     * @throws {PromiseException}
     */
    async remove(target) {
        const url = this.deleteActionPath + this.pageId;

        await this.sendDataToUrl(url);
        try {
            await this.removePageLink(target);
        } catch (error) {
            throw error;
        }
    }

    /**
     * Обрабатывает клик по кнопке "Удалить текущую страницу". Удаляет страницу из базы.
     *
     * @param {HTML} target - Элемент по которому произошел клик
     */
    async removeFromFooter(target) {

        this.pageId = target.getAttribute('data-favoriteid');
        const url = this.deleteActionPath + this.pageId;
        this.footerButton = target;

        await this.sendDataToUrl(url, this.actionTypeDelete);
    }

    /**
     * Отправляет данные на указнный url и меняет кнопки и статус иконки при наличии actionType
     *
     * @param {Sting} actionType - Строка с типом действия
     * @param {String} url - Ссылка на которую fetchAPI совершает GET-запрос
     * @throws {DOMException}
     */
    async sendDataToUrl(url, actionType = '') {
        try {
            const userData = await this.postData(url);

            if (actionType !== '') {
                await this.changeBtnAndStar(userData, actionType);
            }

        } catch (error) {
            alert('Данные не были изменены. Попробуйте ещё раз или обратитесь к администратору.');
            throw error;
        }
    }

    /**
     * Совершает GET запрос через FetchAPI на указанный url
     *
     * @param {String} url - Ссылка на которую fetchAPI совершает GET-запрос
     */
    postData(url = '') {
        return fetch(url, {
            method: 'GET',
            cache: 'no-cache',
            headers: {
                'Content-Type': 'application/json'
            },
            referrer: 'no-referrer',
        })
            .then(response => response.json());
    }
    
    /**
     * Обрабатывает клик по кнопке "Удалить текущую страницу". Удаляет страницу из базы.
     *
     * @param {Array} userData - Ответ от функции postData
     * @param {Sting} actionType - Строка с типом действия
     */
    changeBtnAndStar(userData, actionType = '') {
        const footerTarget = this.footerButton;
        const targetParent = footerTarget.parentNode;
        const footerBtnParent = footerTarget.parentNode.parentNode;
        const star = document.getElementById('favoriteStar');
        const responseUserData = userData[0];

        footerBtnParent.removeChild(targetParent);

        if (actionType === 'save') {
            star.style.color = '#f39c12';
            footerBtnParent.insertAdjacentHTML('beforeend', this.addDeteleBtn(responseUserData));
        } else if (actionType === 'delete') {
            star.style.color = '';
            footerBtnParent.insertAdjacentHTML('beforeend', this.addSaveBtn(responseUserData));
        }
    }

    /**
     * Создает DOM string с нужными параметрами для удаления записи из БД
     *
     * @param {Array} userData - Ответ от функции postData
     * @return {DOMString} DOM string с кнопкой "Удалить эту страницу".
     */
    addDeteleBtn(userData) {
        return `<li class="footer btn btn-danger" style="width: 100%;"><a class="favorites-btn" href="#" data-action="removeFromFooter" data-favoriteId="${userData.id}">Удалить эту страницу</a></li>`;

    }

    /**
     * Создает DOM string с нужными параметрами для добавления записи в БД
     *
     * @param {Array} userData - Элемент по которому произошел клик
     * @return {DOMString} DOM string с кнопкой "Добавить эту страницу в избранное".
     */
    addSaveBtn(userData) {
        return `<li class="footer btn btn-success" id="addPageToFavorites"><a href="#" class="favorites-btn" data-pageType="${userData.pageType}" data-action="save" data-pageName="${userData.userUrlName}" data-pageUrl="${userData.userUrl}">Добавить эту страницу в избранное</a></li>`;
    }

    /**
     * Обрабатывает клик по кнопке "Удалить текущую страницу". Удаляет страницу из базы.
     *
     * @constructor
     * @param {HTML} target - Элемент по которому произошел клик
     */
    removePageLink(target) {
        const parentNode = target.parentNode;

        this.removeHeaders(target);
        this.parentNode.removeChild(parentNode);
    }

    /**
     * Удаляет заголовок с категорией страницы при условии, если удаляемая страница была последней в категории
     *
     * @param {HTML} target - Элемент по которому произошел клик
     */
    removeHeaders(target) {
        const headerClass = "favoritesPageType";
        const header = target.parentNode.previousElementSibling;
        const targetNextSibling = target.parentNode.nextElementSibling;

        if (!targetNextSibling && header.classList.contains(headerClass)) {
            this.parentNode.removeChild(header);
        }
    }

    /**
     * Делегирует события между событиями происходящими в this.element. Подробнее: https://learn.javascript.ru/event-delegation
     *
     */
    listenTarget() {
        let self = this;
        this.element.onclick = function(e) {

            var target = e.target;
            self.pageId = target.getAttribute('data-favoriteId');
            var action = target.getAttribute('data-action');

            if (action) {
                self[action](target);
            }
        };
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const favoritesList = document.getElementById('favoritesList');
    new FavoriteController(favoritesList).listenTarget();
});