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


        $template->parse();
        return $template->get();
    }

    private function pageData(): void
    {
        $className = $this->___getRootClass();

        /** @var ContainerIndexPage $page */
        $container = Container::DIC();
        $page = $container->getDIC('/Page');

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
