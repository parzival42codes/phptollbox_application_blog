<?php declare(strict_types=1);


class ApplicationBlog_install extends ContainerFactoryModulInstall_abstract
{


    public function install(): void
    {
        $this->importLanguage();
        $this->importConfig();
        $this->importMetaFromModul('_app');
        $this->importQueryDatabaseFromCrud('ApplicationBlog_crud');
        $this->importQueryDatabaseFromCrud('ApplicationBlog_crud_category');
        $this->importQueryDatabaseFromCrud('ApplicationBlog_crud_category_link');
        $this->readLanguageFromFile('item');
    }

    public function uninstall(): void
    {
        $this->removeStdEntities();
    }

    public function activate(): void
    {
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
    }

    public function deactivate(): void
    {
        $this->removeStdEntitiesDeactivate();
    }


}
