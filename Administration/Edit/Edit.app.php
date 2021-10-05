<?php declare(strict_types=1);

/**
 * Blog Verwaltung
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    hasCSS
 * @modul    language_path_de_DE /Administration/Anwendung/Blog/Administration
 * @modul    language_name_de_DE Edit
 * @modul    language_path_en_US /Administration/Application/Blog/Administration
 * @modul    language_name_en_US Edit
 */
class ApplicationBlogAdministrationEdit_app extends Application_abstract
{

    public function setContent(): string
    {
        /** @var ContainerFactoryRouter $route */
        $route = Container::getInstance('ContainerFactoryRouter');

        $crud = new ApplicationBlog_crud();
        if (!empty($route->getParameter('id'))) {
            $crud->setCrudId((int)$route->getParameter('id'));
            $crud->findById(true);
        }

        $templateCache = new ContainerExtensionTemplateLoad_cache_template($this->___getRootClass(),
                                                                           'default');

        $template = new ContainerExtensionTemplate();
        $template->set($templateCache->getCacheContent()['default']);

        $formHelper = new ContainerExtensionTemplateParseCreateForm_helper($this->___getRootClass(),
                                                                           'edit');

        $formHelperResponse = $formHelper->getResponse();
        if (
            $formHelperResponse->isHasResponse()
        ) {
            $this->formResponse($formHelper,
                                $crud);
        }

//        /** @var ContainerFactoryDatabaseQuery $query */
//        $query = Container::get('ContainerFactoryDatabaseQuery',
//                                __METHOD__ . '#select',
//                                true,
//                                ContainerFactoryDatabaseQuery::MODE_SELECT);
//        $query->setTable('custom_blog');
//        $query->select('crudId');
//        $query->setParameterWhere('crudIdent',
//                                  $this->id);
//        $query->orderBy('dataVariableCreated DESC');
//
//        $query->construct();
//        $smtp = $query->execute();
//
//        while ($smtpData = $smtp->fetch()) {
//            $crudArray[$smtpData['crudId']] = $smtpData['dataVariableCreated'];
//        }

        $formHelper->addFormElement('crudTitle',
                                    'text',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudTitle()
                                        ]
                                    ]);

        $template->assign('crudTitle',
                          $formHelper->getElements());

        $formHelper->addFormElement('crudContent',
                                    'textarea',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudText()
                                        ]
                                    ]);

        $template->assign('crudContent',
                          $formHelper->getElements());

        $formHelper->addFormElement('crudStatus',
                                    'select',
                                    [
                                        [
                                            'draft'  => ContainerFactoryLanguage::get('/ApplicationBlog/status/draft'),
                                            'hide'   => ContainerFactoryLanguage::get('/ApplicationBlog/status/hide'),
                                            'show'   => ContainerFactoryLanguage::get('/ApplicationBlog/status/show'),
                                            'delete' => ContainerFactoryLanguage::get('/ApplicationBlog/status/delete'),
                                        ]
                                    ],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudStatus()
                                        ]
                                    ]);

        $template->assign('crudStatus',
                          $formHelper->getElements());

        $template->assign('registerHeader',
                          $formHelper->getHeader());

        $template->assign('registerFooter',
                          $formHelper->getFooter());

        $template->assign('datetimeCreated',
                          ContainerHelperDatetime::getLocaleDate($crud->getDataVariableCreated()));
        $template->assign('datetimeUpdated',
                          ContainerHelperDatetime::getLocaleDate($crud->getDataVariableEdited()));
        $template->assign('datetimeDeleted',
                          ContainerHelperDatetime::getLocaleDate($crud->getDataVariableDeleted()));
        $template->assign('viewCount',
                          $crud->getCrudViewCount());
        $template->assign('commentCount',
                          $crud->getAdditionalQuerySelect('commentCount'));

        $template->parse();

        $this->createPageData();

        return $template->get();

    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, ApplicationBlog_crud $crudUser): void
    {


    }

    protected function pageData(): void
    {
        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationBlogAdministration');
    }

}
