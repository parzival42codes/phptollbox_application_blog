<?php

/**
 * Class ApplicationBlog_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 * @database dataVariableDeleted
 *
 */
class ApplicationBlog_crud extends Base_abstract_crud
{

    protected static string $table   = 'custom_blog';
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
    protected string $crudTitle = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudText = '';

    /**
     * @var int
     * @database type int;11
     */
    protected int $crudViewCount = 0;

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
    public function getCrudText(): string
    {
        return $this->crudText;
    }

    /**
     * @param string $crudText
     */
    public function setCrudText(string $crudText): void
    {
        $this->crudText = $crudText;
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
     * @return int
     */
    public function getCrudViewCount(): int
    {
        return $this->crudViewCount;
    }

    /**
     * @param int $crudViewCount
     */
    public function setCrudViewCount(int $crudViewCount): void
    {
        $this->crudViewCount = $crudViewCount;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('custom_blog_category_link',
                     [],
                     'custom_blog_category_link.crudBlogId = ' . self::$table . '.crudId');

        $query->join('custom_blog_category',
                     [
                         'crudPath',
                         'crudTitle',
                         'crudLanguage',
                     ],
                     'custom_blog_category_link.crudCategoryId = custom_blog_category.crudId');

        $query->join('comments',
                     [],
                     'comments.crudPath = concat(custom_blog_category.crudPath,"/",custom_blog_category.crudTitle,"/",' . self::$table . '.crudId)');

        $query->selectFunction('count(comments.crudId) as commentCount');
        $query->selectFunction('concat(custom_blog_category.crudPath,"/",custom_blog_category.crudTitle,"/",' . self::$table . '.crudId)');

        $query->groupBy(self::$table . '.crudId');

        d($query->getQueryParsed());

        return $query;
    }

}
