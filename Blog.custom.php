<?php
declare(strict_types=1);

/**
 * @modul name Template Tooltip
 * @modul description Template Tooltip
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 * @modul hasCSS
 * @modul hasJavascript
 *
 */
class ApplicationBlog_custom extends Custom_abstract
{
    protected array $iniData = [];

    public function __construct()
    {
        /** @var ContainerFactoryLanguageParseIni $iniLang */
        $iniLang = Container::get('ContainerFactoryLanguageParseIni',
                                  '
[de_DE]
name="Blog"
description="Blog"
        ');

        $this->iniData = $iniLang->get();
    }

    public function getName(): string
    {
        return $this->iniData['name'];
    }

    public function getDescription(): string
    {
        return $this->iniData['description'];
    }

    public function getDependencies(): array
    {
        return [
            'ApplicationBlog',
            'ApplicationBlogView',
            'ApplicationBlogAdministration',
        ];
    }

}
