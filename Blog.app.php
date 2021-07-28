<?php declare(strict_types=1);

/**
 * Dreamtrip
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    language_path_de_DE Blog
 * @modul    language_name_de_DE Blog
 * @modul    language_path_en_US Blog
 * @modul    language_name_en_US Blog
 */
class ApplicationBlog_app extends Application_abstract
{
    public function setContent(): string
    {
        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default,item');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $crud = new ApplicationBlog_crud();

//        $filter = new ContainerExtensionTemplateParseCreateFilterHelper('blog');
//
//        $filter->addFilter('dataVariableCreatedFrom',
//                           null,
//                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/date/from',
//                                                         [
//                                                             'de_DE' => 'Datum von',
//                                                             'en_US' => 'Date from',
//                                                         ]),
//                           'date');
//
//        $filter->addFilter('dataVariableCreatedTo',
//                           null,
//                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/date/to',
//                                                         [
//                                                             'de_DE' => 'Datum bis',
//                                                             'en_US' => 'Date to',
//                                                         ]),
//                           'date');
//
//        $filter->addFilter('dataVariableTags',
//                           null,
//                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/tags',
//                                                         [
//                                                             'de_DE' => 'Tags',
//                                                             'en_US' => 'Tags',
//                                                         ]),
//                           'input');
//
//        $filter->create();
//        $filterValues = $filter->getFilterValues();

        $filterCrud = [];
//        if (isset($filterValues['dataVariableCreatedFrom']) && $filterValues['dataVariableCreatedFrom'] !== '') {
//            $filterCrud['dataVariableCreatedFrom'] = $filterValues['dataVariableCreatedFrom'];
//        }
//        if (isset($filterValues['dataVariableCreatedTo']) && $filterValues['dataVariableCreatedTo'] !== '') {
//            $filterCrud['dataVariableCreatedTo'] = $filterValues['dataVariableCreatedTo'];
//        }
//        if (isset($filterValues['dataVariableTags']) && $filterValues['dataVariableTags'] !== '') {
//            $filterCrud['dataVariableTags'] = $filterValues['dataVariableTags'];
//        }

        $count = $crud->count($filterCrud);

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'blog',
                                     $count);
        $pagination->create();

        $crudImports = $crud->find($filterCrud,
                                   [],
                                   [
                                       'dataVariableCreated DESC'
                                   ],
                                   $pagination->getPagesView(),
                                   $pagination->getPageOffset());

        $entriesContent = '';

        /** @var ApplicationBlog_crud $crudItem */
        foreach ($crudImports as $crudItem) {
            $crudItemDate = new DateTime($crudItem->getDataVariableCreated());

            $templateEntry = new ContainerExtensionTemplate();
            $templateEntry->set($templateCache->getCacheContent()['item']);
            $templateEntry->assign('title',
                                   $crudItem->getCrudTitle());
            $templateEntry->assign('date',
                                   $crudItemDate->format((string)Config::get('/environment/datetime/format')));
            $templateEntry->assign('content',
                                   $crudItem->getCrudText());
            $templateEntry->parse();

            $entriesContent .= $templateEntry->get();

        }

        $this->createMenu();

        $template->assign('entries',
                          $entriesContent);

        $template->parse();
        return $template->get();
    }

    private function createMenu()
    {
        $menuObj = new ContainerFactoryMenu();

        $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                   true,
                                                   ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('custom_blog');
        $query->groupBy('dataVariableCreated');

        $query->construct();
        $smtp = $query->execute();

        while ($item = $smtp->fetch()) {
          d($item);
        }

        eol();
    }

    private function pageData(): void
    {
        $className = $this->___getRootClass();

        /** @var ContainerIndexPage $page */
        $container = Container::DIC();
        $page      = $container->getDIC('/Page');

        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $className . '/meta/title'));
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $className . '/meta/description'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=' . $className . '');

        $breadcrumb = $page->getBreadcrumb();

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $className . '/meta/title'),
                                       'index.php?application=' . $className);

        $menu = $this->getMenu();
        $menu->setMenuClassMain($this->___getRootClass());

    }


}
