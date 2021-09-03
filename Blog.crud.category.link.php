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
     * @var int
     * @database type int;11
     * @isIndex
     */
    protected int $crudBlogId = 0;
    /**
     * @var int
     * @database type int;11
     * @isIndex
     */
    protected int $crudCategoryId= 0;

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
     * @return int
     */
    public function getCrudBlogId(): int
    {
        return $this->crudBlogId;
    }

    /**
     * @param int $crudBlogId
     */
    public function setCrudBlogId(int $crudBlogId): void
    {
        $this->crudBlogId = $crudBlogId;
    }

    /**
     * @return int
     */
    public function getCrudCategoryId(): int
    {
        return $this->crudCategoryId;
    }

    /**
     * @param int $crudCategoryId
     */
    public function setCrudCategoryId(int $crudCategoryId): void
    {
        $this->crudCategoryId = $crudCategoryId;
    }

}
