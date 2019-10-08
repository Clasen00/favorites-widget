<div class="dropdown notifications-menu pull-left col-md-1" style="top: 4px;left: -25px;">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-star" id="favoriteStar" style="<?= (!empty($currentModel)) ? 'color: #f39c12 !important;' : '' ?>" title="Избранное"></i>
    </a>
    <ul class="dropdown-menu" id="favoritesList" style="margin-left: -95px !important;">
        <li>
            <ul class="menu" id="menuElements" style="max-height: 100%;list-style: none;padding: 0px;">
                <?php foreach ($urlTypeArray as $urlTypeKey): ?>
                <li class="header favoritesPageType"><?= $urlTypeKey ?></li>
                    <?php foreach ($model as $key => $value): ?>
                        <?php if ($value->url_type === $urlTypeKey && $currentModel != $value->id): ?>
                            <li title="Страница" class="favorites-list-item" style="padding: 10px 7px 10px 10px; overflow-x: auto">
                                <a href="<?= $value->url ?>" style="padding-right: 10px;">
                                    <?= $value->url_name ?>
                                </a>
                                <i class="fa fa-remove" id="remove-<?= $value->id ?>" data-action="remove" data-favoriteId="<?= $value->id ?>" style="cursor: pointer;" title="Удалить страницу из избранного"></i>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php if (!empty($currentModel)): ?>
        <li class="footer btn btn-danger favorites-btn-footer"><a class="favorites-btn" href="#" data-action="removeFromFooter" data-favoriteId="<?= $currentModel ?>">Удалить эту страницу</a></li>
        <?php elseif (!empty($pageType)): ?>
        <li class="footer btn btn-success favorites-btn-footer" id="addPageToFavorites"><a href="#" class="favorites-btn" data-pageType="<?= $pageType ?>" data-action="save" data-pageName="<?= str_replace('"', '', $this->title) ?>" data-pageUrl="<?= Yii::$app->request->url ?>">Добавить эту страницу в избранное</a></li>
        <?php else: ?>
            <li class="footer disabled" title="Только создаваемый контент может быть добавлен в избранное, такой как: новости, конференции, голосования, опросы и проекты"><a class="disabled" href="#" onclick="return false">Нельзя добавить эту страницу в избранное </a></li>
            <?php endif; ?>
    </ul>
</div>