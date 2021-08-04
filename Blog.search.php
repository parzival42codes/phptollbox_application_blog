<?php declare(strict_types=1);

class ApplicationBlog_search extends ApplicationSearch_abstract
{
    public function getForm(ContainerExtensionTemplateParseCreateForm_helper $formHelper): string
    {
        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'search');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['search']);

        $formHelper->addFormElement('dateFrom',
                                    'date');

        $formHelper->addFormElement('dateTo',
                                    'date');

        $template->assign('date',
                          $formHelper->getElements(true));

        $template->parse();

        return $template->get();

    }


}
