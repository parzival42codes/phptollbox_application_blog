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
    private array $dateCollect             = [];
    private array $dateCollectCounterYear  = [];
    private array $dateCollectCounterMonth = [];

    public function setContent(): string
    {
        $this->collectDateInfo();
        $this->createPageData();

        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default,item');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $crud = new ApplicationBlog_crud();

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container->getDIC('/Router');

        $filterCrud = [];

        if ($router->getRoute() === 'filtercategory') {
            $filterCrud = [
                'crudCategoryId' => $router->getParameter('category')
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

        $crudImports = $crud->find($filterCrud,
                                   [
                                       'dataVariableCreated DESC'
                                   ],
                                   [],
                                   $pagination->getPagesView(),
                                   $pagination->getPageOffset());

        $entriesContent = '';

        /** @var ApplicationBlog_crud $crudItem */
        foreach ($crudImports as $crudItem) {
            $crudItemDate = new DateTime($crudItem->getDataVariableCreated());

            $blogText = $crudItem->getCrudText();

            if (str_word_count($blogText) > (int)Config::get('/ApplicationBlog/words/max')) {
                $blogTextExplode = explode(' ',
                                           $blogText,
                    ((int)Config::get('/ApplicationBlog/words/max') + 1));
                array_pop($blogTextExplode);

                $blogText = implode(' ',
                                    $blogTextExplode) . ' ' . ContainerFactoryLanguage::get('/ApplicationBlog/ellipse');
            }

            $categoryText = ContainerFactoryLanguage::getLanguageText($crudItem->getAdditionalQuerySelect('custom_blog_category_crudLanguage'));

            $templateEntry = new ContainerExtensionTemplate();
            $templateEntry->set($templateCache->getCacheContent()['item']);
            $templateEntry->assign('id',
                                   $crudItem->getCrudId());
            $templateEntry->assign('title',
                                   $crudItem->getCrudTitle());
            $templateEntry->assign('titleUrl',
                                   urlencode($crudItem->getCrudTitle()));
            $templateEntry->assign('date',
                                   $crudItemDate->format((string)Config::get('/environment/datetime/format')));
            $templateEntry->assign('category',
                                   $categoryText);
            $templateEntry->assign('viewCount',
                                   $crudItem->getCrudViewCount());
            $templateEntry->assign('commentCount',
                                   $crudItem->getAdditionalQuerySelect('commentCount'));
            $templateEntry->assign('content',
                                   $blogText);
            $templateEntry->parse();

            $entriesContent .= $templateEntry->get();

        };

        if ($router->getRoute() === 'filtercategory') {
            $categoryName = ContainerFactoryLanguage::get('/ApplicationBlog/category');

            $text        = ContainerFactoryLanguage::getLanguageText($crudItem->getAdditionalQuerySelect('custom_blog_category_crudLanguage'));
            $textExplode = explode('/',
                                   $text);
            $title       = array_pop($textExplode);
            $path        = implode('/',
                                   $textExplode);

            $template->assign('menu',
                              $this->createMenu('/' . $categoryName . '/' . $path,
                                                $title));
        }
        elseif ($router->getRoute() === 'filterdate') {

            $dateName = ContainerFactoryLanguage::get('/ApplicationBlog/date');

            $date = new DateTime($crudItem->getDataVariableCreated());

            $title = strftime("%B",
                              $date->getTimestamp()) . ' (' . $this->dateCollectCounterMonth[$router->getParameter('year')][$router->getParameter('month')] . ')';
            $template->assign('menu',
                              $this->createMenu('/' . $dateName . '/' . $router->getParameter('year') . ' (' . $this->dateCollectCounterYear[$router->getParameter('year')] . ')',
                                                $title));
        }
        else {
            $template->assign('menu',
                              $this->createMenu());
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
        ContainerFactoryLanguage::get('/ApplicationBlog/category');
        $user = Container::getInstance('ContainerFactoryUser');

        $menuObj = new ContainerFactoryMenu();
        $menuObj->setMenuAccessList($user->getUserAccess());

        foreach ($this->dateCollect as $dateItemYear => $dateItemMonthData) {
            foreach ($dateItemMonthData as $dateItemMonth => $dateItemMonthItem) {
                $path  = '/' . $dateName . '/' . $dateItemYear . ' (' . ($this->dateCollectCounterYear[$dateItemYear] ?? 0) . ')';
                $title = strftime("%B",
                                  $dateItemMonthItem) . ' (' . $this->dateCollectCounterMonth[$dateItemYear][$dateItemMonth] . ')';

                $menuItem = new ContainerFactoryMenuItem();
                $menuItem->setPath($path);
                $menuItem->setTitle($title);
                $menuItem->setLink('index.php?application=ApplicationBlog&route=filterdate&year=' . $dateItemYear . '&month=' . $dateItemMonth);
                $menuItem->setAccess('ApplicationBlog');

                $menuObj->addMenuItem($menuItem);
            }
        }

        $crudCategory     = new ApplicationBlog_crud_category();
        $crudCategoryFind = $crudCategory->find();

        /** @var ApplicationBlog_crud_category $crudCategoryFindItem */
        foreach ($crudCategoryFind as $crudCategoryFindItem) {
            $text        = ContainerFactoryLanguage::getLanguageText($crudCategoryFindItem->getCrudLanguage());
            $textExplode = explode('/',
                                   $text);
            $title       = array_pop($textExplode);
            $path        = implode('/',
                                   $textExplode);

            $menuItem = new ContainerFactoryMenuItem();
            $menuItem->setPath('/' . $categoryName . '/' . $path);
            $menuItem->setTitle($title);
            $menuItem->setLink('index.php?application=ApplicationBlog&route=filtercategory&category=' . $crudCategoryFindItem->getCrudId());
            $menuItem->setAccess('ApplicationBlog');

            $menuObj->addMenuItem($menuItem);
        }

//        d($routerPath);
//        d($routerTitle);
//        d($menuObj);
//        eol();

        return $menuObj->createMenu($routerPath,
                                    $routerTitle);
    }

    private function pageData(): void
    {
//        $className = Core::getRootClass(__CLASS__);
//
//        /** @var ContainerIndexPage $page */
//        $page = Container::getInstance('ContainerIndexPage');
//
//        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $className . '/meta/title'));
//        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $className . '/meta/description'));
//
//        /** @var ContainerFactoryRouter $router */
//        $router = Container::get('ContainerFactoryRouter');
//        $router->analyzeUrl('index.php?application=' . $className . '');
//
//        $breadcrumb = $page->getBreadcrumb();
//
//        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $className . '/meta/title'),
//                                       'index.php?application=' . $className);

//        $menu = $this->getMenu();
//        $menu->setMenuClassMain($this->___getRootClass());

    }

    public function collectDateInfo(): void
    {
        $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                   true,
                                                   ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->selectFunction('DATE(dataVariableCreated) as createdDate');
        $query->setTable('custom_blog');
        $query->groupBy('DATE(dataVariableCreated)',
                        false);

        $query->construct();
        $smtp = $query->execute();

        while ($item = $smtp->fetch()) {
            $dateTime = new DateTime($item['createdDate']);

            if (isset($this->dateCollectCounterYear[$dateTime->format('Y')])) {
                $this->dateCollectCounterYear[$dateTime->format('Y')] += 1;
            }
            else {
                $this->dateCollectCounterYear[$dateTime->format('Y')] = 1;
            }

            if (isset($this->dateCollectCounterMonth[$dateTime->format('Y')][$dateTime->format('m')])) {
                $this->dateCollectCounterMonth[$dateTime->format('Y')][$dateTime->format('m')] += 1;
            }
            else {
                $this->dateCollectCounterMonth[$dateTime->format('Y')][$dateTime->format('m')] = 1;
            }

            $this->dateCollect[$dateTime->format('Y')][$dateTime->format('m')] = $dateTime->getTimestamp();

        }
    }
}
