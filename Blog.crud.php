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
     * @var ?int
     * @database isNull
     * @database isIndex
     * @database type int;11
     */
    protected ?int $crudParentId = null;
    /**
     * @var string
     * @database type enum;"draft","show","hide"
     * @database default draft
     */
    protected string $crudStatus = 'draft';
    /**
     * @var ?int
     * @database type int;11
     */
    protected ?int $crudUserId = null;
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
     * @var string
     * @database type varchar;10
     */
    protected string $crudLanguage = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudCategory = '';
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

    /**
     * @return int|null
     */
    public function getCrudUserId(): ?int
    {
        return $this->crudUserId;
    }

    /**
     * @param int|null $crudUserId
     */
    public function setCrudUserId(?int $crudUserId): void
    {
        $this->crudUserId = $crudUserId;
    }

    /**
     * @return string
     */
    public function getCrudStatus(): string
    {
        return $this->crudStatus;
    }

    /**
     * @param string $crudStatus
     */
    public function setCrudStatus(string $crudStatus): void
    {
        $this->crudStatus = $crudStatus;
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

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('comments',
                     [],
                     'comments.crudModul = "ApplicationBlog" AND comments.crudModulId = ' . self::$table . '.crudId');

        $query->selectFunction('count(comments.crudId) as commentCount');

        $query->groupBy(self::$table . '.crudId');

        return $query;
    }

}
