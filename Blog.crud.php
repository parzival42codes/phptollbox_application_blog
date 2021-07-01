<?php

class ApplicationBlog_crud extends Base_abstract_crud
{

    protected static string $table   = 'custom_dreamptrip';
    protected static string $tableId = 'crudIdent';

    /**
     * @var string $crudIdent
     * @database type varchar;250
     * @database isPrimary
     *
     */
    protected string $crudIdent = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudPath = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudData = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudText = '';


    /**
     * @return string
     */
    public function getCrudIdent(): string
    {
        return $this->crudIdent;
    }

    /**
     * @param string $crudIdent
     */
    public function setCrudIdent(string $crudIdent): void
    {
        $this->crudIdent = $crudIdent;
    }

    /**
     * @return string
     */
    public function getCrudData(): string
    {
        return $this->crudData;
    }

    /**
     * @param string $crudData
     */
    public function setCrudData(string $crudData): void
    {
        $this->crudData = $crudData;
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
