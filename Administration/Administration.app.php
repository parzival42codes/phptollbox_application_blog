<?php declare(strict_types=1);

/**
 * Blog Verwaltung
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    language_path_de_DE /Administration/Anwendung
 * @modul    language_name_de_DE Blog Administration
 * @modul    language_path_en_US /Administration/Application
 * @modul    language_name_en_US Blog Administration
 */
class ApplicationBlogAdministration_app extends Application_abstract
{
    public function setContent(): string
    {
        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $filterDataCategoryPath = $this->getFilterDataCategoryPath();

        /** @var ContainerExtensionTemplateParseCreateFilterHelper $filter */
        $filter = Container::get('ContainerExtensionTemplateParseCreateFilterHelper',
                                 'blog');

        $filter->addFilter('crudCategoryPath',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationBlogAdministration/filter/header/crudCategoryPath'),
                           'select',
                           $filterDataCategoryPath);

        $filter->create();

        $filterValues = $filter->getFilterValues();
        $filterCrud   = [];
        if (isset($filterValues['crudCategoryPath']) && !empty($filterValues['crudCategoryPath'])) {
            $filterCrud['crudType'] = $filterValues['crudCategoryPath'];
        }

        $crud = new ApplicationBlog_crud();

        $count = $crud->count($filterCrud);

        $pagination = new ContainerExtensionTemplateParseCreatePaginationHelper('blog',
                                                                                $count);
        $pagination->create();

        $crudResult = $crud->find($filterCrud,
                                  [
                                      'crudId DESC'
                                  ],
                                  [],
                                  $pagination->getPagesView(),
                                  $pagination->getPageOffset());

//        d($crudResult);
//        eol();

        $this->createPageData();

        $tableTcs = [];

        /** @var ApplicationBlog_crud $crudResultItem */
        foreach ($crudResult as $crudResultItem) {
            /** @var ContainerFactoryRouter $editRouter */
            $editRouter = Container::get('ContainerFactoryRouter');
            $editRouter->setRoute('edit');
            $editRouter->setApplication('ApplicationAdministrationUserEdit');
            $editRouter->setParameter('id',
                                      $crudResultItem->getCrudId());

            $blogText = $crudResultItem->getCrudText();

            if (str_word_count($blogText) > (int)Config::get('/ApplicationBlog/words/max')) {
                $blogTextExplode = explode(' ',
                                           $blogText,
                    ((int)Config::get('/ApplicationBlog/words/max') + 1));
                array_pop($blogTextExplode);

                $blogText = implode(' ',
                                    $blogTextExplode) . ' ' . ContainerFactoryLanguage::get('/ApplicationBlog/ellipse');
            }

            $tableTcs[] = [
                'crudId'              => $crudResultItem->getCrudId(),
                'crudStatus'          => ContainerFactoryLanguage::get('/ApplicationBlog/status/' . $crudResultItem->getCrudStatus()),
                'crudTitle'           => $crudResultItem->getCrudTitle(),
                'crudText'            => $blogText,
                'categoryCategory'    => ContainerFactoryLanguage::getLanguageText($crudResultItem->getAdditionalQuerySelect('custom_blog_category_crudLanguage')),
                'crudViewCount'       => $crudResultItem->getCrudViewCount(),
                'commentCount'        => $crudResultItem->getAdditionalQuerySelect('commentCount'),
                'dataVariableCreated' => ContainerHelperDatetime::getLocaleDate($crudResultItem->getDataVariableCreated()),
                'dataVariableEdited' => ContainerHelperDatetime::getLocaleDate($crudResultItem->getDataVariableEdited()),
                'dataVariableDeleted' => ContainerHelperDatetime::getLocaleDate($crudResultItem->getDataVariableDeleted()),
                'action'              => '',
                //                'edit'            => '<a href="' . $editRouter->getUrlReadable() . '" class="btn">{insert/resources resource="icon" icon="edit"}</a>',
            ];
        }

        $template->assign('Table_Table',
                          $tableTcs);

        $template->parse();

        return $template->get();
    }

    public function pageData(): void
    {

    }

    public static function createMenu(string $class): void
    {
        /** @var ContainerFactoryMenu $menu */
        $menu = Container::get('ContainerFactoryMenu',
                               ContainerFactoryMenu::MENU_HORIZONTAL);
        $menu->setIsTab(true);
        $menu->setMenuAccessList();

        /** @var ContainerFactoryMenuItem $menuItemOverview */
        $menuItemOverview = Container::
        get('ContainerFactoryMenuItem');
        $menuItemOverview->setAccess('');
        $menuItemOverview->setLink('index.php?application=ApplicationAdministrationUser');
        $menuItemOverview->setPath('/');
        $menuItemOverview->setTitle('1|' . ContainerFactoryLanguage::get('/ApplicationAdministrationUser/meta/title'));

        $menu->addMenuItem($menuItemOverview);

        /** @var ContainerFactoryMenuItem $menuItemEdit */
        $menuItemEdit = Container::get('ContainerFactoryMenuItem');
        $menuItemEdit->setAccess('');
        $menuItemEdit->setLink('index.php?application=ApplicationAdministrationUserEdit');
        $menuItemEdit->setPath('/');
        $menuItemEdit->setTitle('2|' . sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserEdit/meta/title'),
                                               ''));

        $menu->addMenuItem($menuItemEdit);

        ContainerExtensionTemplateParseInsertPositions::insert('/page/box/main/header',
                                                               $menu->createMenu('/',
                                                                                 sprintf(ContainerFactoryLanguage::get('/' . $class . '/meta/title'),
                                                                                         '')));

    }

    protected function getFilterDataCategoryPath(): array
    {

        $crud      = new ApplicationBlog_crud_category();
        $crudItems = $crud->find();

        $filterData = [];

        /** @var ApplicationBlog_crud_category $crudItem */
        foreach ($crudItems as $crudItem) {
            $text                               = ContainerFactoryLanguage::getLanguageText($crudItem->getCrudLanguage());
            $filterData[$crudItem->getCrudId()] = $text;
        }

        return $filterData;
    }

}
