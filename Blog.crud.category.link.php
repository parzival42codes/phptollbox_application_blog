<?php

class ApplicationBlog_crud_category_link extends Base_abstract_crud
{

    protected static string $table   = 'custom_blog_category_link';
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
     * @isIndex
     */
    protected string $crudBlogId = '';
    /**
     * @var string
     * @database type varchar;200
     * @isIndex
     */
    protected string $crudCategoryId= '';

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
    public function getCrudBlogId(): string
    {
        return $this->crudBlogId;
    }

    /**
     * @param string $crudBlogId
     */
    public function setCrudBlogId(string $crudBlogId): void
    {
        $this->crudBlogId = $crudBlogId;
    }

    /**
     * @return string
     */
    public function getCrudCategoryId(): string
    {
        return $this->crudCategoryId;
    }

    /**
     * @param string $crudCategoryId
     */
    public function setCrudCategoryId(string $crudCategoryId): void
    {
        $this->crudCategoryId = $crudCategoryId;
    }

}
