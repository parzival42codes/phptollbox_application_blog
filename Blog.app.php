<?php declare(strict_types=1);

/**
 * Blog
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    hasSearch
 * @modul    language_path_de_DE Blog
 * @modul    language_name_de_DE Blog
 * @modul    language_path_en_US Blog
 * @modul    language_name_en_US Blog
 */
class ApplicationBlog_app extends Application_abstract
{
    public function setContent(): string
    {
        $this->pageData();

        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default,item');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $crud = new ApplicationBlog_crud();

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container->getDIC('/Router');

        d($router);

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

        };

        if ($router->getRoute() === 'filtercategory') {
            $categoryName = ContainerFactoryLanguage::get('/ApplicationBlog/category');

            $template->assign('menu',
                              $this->createMenu('/' . $categoryName . '/' . $crudItem->getAdditionalQuerySelect('custom_blog_category_crudPath'),
                                                $crudItem->getAdditionalQuerySelect('custom_blog_category_crudTitle')));
        }
        elseif ($router->getRoute() === 'filterdate') {
//             . '-' . $router->getParameter('month')

               $date = new DateTime($crudItem->getDataVariableCreated());

//               $title = strftime("%B",
//                                 $date->getTimestamp()) . ' (' . $dateCollectCounterMonth[$dateItemYear][$dateItemMonth] . ')';

            $template->assign('menu',
                              $this->createMenu($router->getParameter('year')),$title);
        }

        $template->assign('entries',
                          $entriesContent);


        $template->parse();
        return $template->get();
    }

    private function createMenu(string $routerPath = '', string $routerTitle = '')
    {
        $dateName     = ContainerFactoryLanguage::get('/ApplicationBlog/date');
        $categoryName = ContainerFactoryLanguage::get('/ApplicationBlog/category');

        $user = Container::getInstance('ContainerFactoryUser');

        $menuObj = new ContainerFactoryMenu();
        $menuObj->setMenuAccessList($user->getUserAccess());

        $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                   true,
                                                   ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->selectFunction('DATE(dataVariableCreated) as createdDate');
        $query->setTable('custom_blog');
        $query->groupBy('DATE(dataVariableCreated)',
                        false);

        $query->construct();
        $smtp = $query->execute();

        $dateCollect             = [];
        $dateCollectCounterYear  = [];
        $dateCollectCounterMonth = [];

        while ($item = $smtp->fetch()) {
            $dateTime = new DateTime($item['createdDate']);

            if (isset($dateCollectCounterYear[$dateTime->format('Y')])) {
                $dateCollectCounterYear[$dateTime->format('Y')] += 1;
            }
            else {
                $dateCollectCounterYear[$dateTime->format('Y')] = 1;
            }

            if (isset($dateCollectCounterMonth[$dateTime->format('Y')][$dateTime->format('m')])) {
                $dateCollectCounterMonth[$dateTime->format('Y')][$dateTime->format('m')] += 1;
            }
            else {
                $dateCollectCounterMonth[$dateTime->format('Y')][$dateTime->format('m')] = 1;
            }

            $dateCollect[$dateTime->format('Y')][$dateTime->format('m')] = $dateTime->getTimestamp();

        }

        foreach ($dateCollect as $dateItemYear => $dateItemMonthData) {
            foreach ($dateItemMonthData as $dateItemMonth => $dateItemMonthItem) {
                $path  = '/' . $dateName . '/' . $dateItemYear . ' (' . ($dateCollectCounterYear[$dateItemYear] ?? 0) . ')';
                $title = strftime("%B",
                                  $dateItemMonthItem) . ' (' . $dateCollectCounterMonth[$dateItemYear][$dateItemMonth] . ')';

                $menuItem = new ContainerFactoryMenuItem();
                $menuItem->setPath($path);
                $menuItem->setTitle($title);
                $menuItem->setLink('index.php?application=ApplicationBlog&route=filterdate&year=' . $dateItemYear . '&month=' . $dateItemMonth);
                $menuItem->setAccess('ApplicationBlog');

                $menuObj->addMenuItem($menuItem);
            }
        }

        $crudCategory     = new ApplicationBlog_crud_category();
        $crudCategoryFind = $crudCategory->find([
                                                    'crudLanguage' => (string)Config::get('/environment/language')
                                                ]);

        /** @var ApplicationBlog_crud_category $crudCategoryFindItem */
        foreach ($crudCategoryFind as $crudCategoryFindItem) {
            $menuItem = new ContainerFactoryMenuItem();
            $menuItem->setPath('/' . $categoryName . '/' . $crudCategoryFindItem->getCrudPath());
            $menuItem->setTitle($crudCategoryFindItem->getCrudTitle());
            $menuItem->setLink('index.php?application=ApplicationBlog&route=filtercategory&category=' . $crudCategoryFindItem->getCrudId());
            $menuItem->setAccess('ApplicationBlog');

            $menuObj->addMenuItem($menuItem);
        }

//        d($routerPath);
//        d($routerTitle);
//        eol();

        return $menuObj->createMenu($routerPath,
                                    $routerTitle);
    }

    private function pageData(): void
    {
        $className = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

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
