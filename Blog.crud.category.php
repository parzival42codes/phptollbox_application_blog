<?php

/**
 * Class ApplicationBlog_crud
 *
 */
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
     * @database type varchar;250
     */
    protected string $crudCategory = '';
    /**
     * @var string
     * @database type text
     */

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
    public function getCrudCategory(): string
    {
        return $this->crudCategory;
    }

    /**
     * @param string $crudCategory
     */
    public function setCrudCategory(string $crudCategory): void
    {
        $this->crudCategory = $crudCategory;
    }

}
