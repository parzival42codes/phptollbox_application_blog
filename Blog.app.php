<?php declare(strict_types=1);

/**
 * Blog
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

        $filterCrud = [];

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

        $template->assign('entries',
                          $entriesContent);
        $template->assign('menu',
                          $this->createMenu());

        $template->parse();
        return $template->get();
    }

    private function createMenu()
    {
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
                $path  = '/' . $dateItemYear . ' (' . ($dateCollectCounterYear[$dateItemYear] ?? 0) . ')';
                $title = strftime("%B",
                                  $dateItemMonthItem) . ' (' . $dateCollectCounterMonth[$dateItemYear][$dateItemMonth] . ')';

                $menuItem = new ContainerFactoryMenuItem();
                $menuItem->setPath($path);
                $menuItem->setTitle($title);
                $menuItem->setLink('index.php?application=ApplicationBlog');
                $menuItem->setAccess('ApplicationBlog');

                $menuObj->addMenuItem($menuItem);
            }
        }

        return $menuObj->createMenu();
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
