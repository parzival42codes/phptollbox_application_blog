<?php declare(strict_types=1);


class ApplicationBlog_install extends ContainerFactoryModulInstall_abstract
{


    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importQueryDatabaseFromCrud('ApplicationBlog_crud');
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
