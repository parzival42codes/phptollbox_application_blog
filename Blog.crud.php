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
    private  ?int $crudId = null;
    /**
     * @var string
     * @database type text
     */
    private string $crudText = '';

    /**
     * @var int
     * @database type int;11
     */
    protected int $crudView = 0;

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
     * @return int
     */
    public function getCrudView(): int
    {
        return $this->crudView;
    }

    /**
     * @param int $crudView
     */
    public function setCrudView(int $crudView): void
    {
        $this->crudView = $crudView;
    }

}
