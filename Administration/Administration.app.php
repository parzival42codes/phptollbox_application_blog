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
        if (isset($filterValues['crudType']) && !empty($filterValues['crudType'])) {
            $filterCrud['crudType'] = $filterValues['crudType'];
        }

        $crud = new ApplicationBlog_crud();

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container->getDIC('/Router');

        $filterCrud = [];

        if ($router->getRoute() === 'filtercategory') {
            $filterCrud = [
                'custom_blog_category_link.crudCategoryId' => $router->getParameter('category')
            ];
        }
        elseif ($router->getRoute() === 'filterdate') {
            $filterCrud = [
                'dataVariableCreated' => $router->getParameter('year') . '-' . $router->getParameter('month') . '%',
            ];
        }

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

        d($crudResult);
        eol();

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

            $tableTcs[] = [
                'crudId'              => $crudResultItem->getCrudId(),
                'crudTitle'           => $crudResultItem->getCrudTitle(),
                'crudText'            => $crudResultItem->getCrudText(),
                'categoryCategory'    => $crudResultItem->getAdditionalQuerySelect('categoryPath'),
                'crudViewCount'       => $crudResultItem->getCrudViewCount(),
                'commentCount'        => $crudResultItem->getAdditionalQuerySelect('commentCount'),
                'dataVariableCreated' => $crudResultItem->getDataVariableCreated(),
                'dataVariableEdited'  => $crudResultItem->getDataVariableEdited(),
                'dataVariableDeleted' => $crudResultItem->getDataVariableDeleted(),
                'edit'                => '',
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

        $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                   true,
                                                   ContainerFactoryDatabaseQuery::MODE_SELECT);
       $query->select('crudId');
       $query->select('crudPath');
       $query->select('crudTitle');
        $query->setTable('custom_blog_category');

        $query->construct();
        $smtp = $query->execute();

        while ($dbData = $smtp->fetch()) {
            d($dbData);
        }

        $filterData = [];
        return $filterData;
    }

}
