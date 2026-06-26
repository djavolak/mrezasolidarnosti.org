<?php

namespace Solidarity\Backend\Controller;
use Laminas\Config\Config;
use Laminas\Session\SessionManager as Session;
use League\Plates\Engine;
use Skeletor\Page\Service\Page;
use Tamtamchik\SimpleFlash\Flash;
use Skeletor\Core\Controller\AjaxCrudController;


class PageController extends AjaxCrudController
{
    const TITLE_VIEW = "View pages";
    const TITLE_CREATE = "Create page";
    const TITLE_UPDATE = "Edit page: ";
    const TITLE_UPDATE_SUCCESS = "Page updated successfully.";
    const TITLE_CREATE_SUCCESS = "Page created successfully.";
    const TITLE_DELETE_SUCCESS = "Page deleted successfully.";
    const PATH = 'Page';
    const FORM_TITLE_ENTITY_IDENTIFIER = 'title';

    public function __construct(
        Page $pageService, Session $session, Config $config, Flash $flash, Engine $template) {
        parent::__construct($pageService, $session, $config, $flash, $template);
    }
}