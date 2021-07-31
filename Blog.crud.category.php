<?php

class ApplicationBlog_crud_category extends Base_abstract_crud
{

    protected static string $table   = 'custom_blog_category';
    protected static string $tableId = 'crudId';

    /**
     * @var int|null
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var string
     * @database type varchar;200
     */
    protected string $crudPath = '';
    /**
     * @var string
     * @database type varchar;200
     */
    protected string $crudTitle = '';
    /**
     * @var string
     * @database type varchar;200
     */
    protected string $crudLanguage = '';

    /**
     * @return int|null
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param int|null $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudTitle(): string
    {
        return $this->crudTitle;
    }

    /**
     * @param string $crudTitle
     */
    public function setCrudTitle(string $crudTitle): void
    {
        $this->crudTitle = $crudTitle;
    }

    /**
     * @return string
     */
    public function getCrudLanguage(): string
    {
        return $this->crudLanguage;
    }

    /**
     * @param string $crudLanguage
     */
    public function setCrudLanguage(string $crudLanguage): void
    {
        $this->crudLanguage = $crudLanguage;
    }

    /**
     * @return string
     */
    public function getCrudPath(): string
    {
        return $this->crudPath;
    }

    /**
     * @param string $crudPath
     */
    public function setCrudPath(string $crudPath): void
    {
        $this->crudPath = $crudPath;
    }


}
