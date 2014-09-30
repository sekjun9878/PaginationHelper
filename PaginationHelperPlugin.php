<?php
namespace sekjun9878\Plugin\PaginationHelper;

use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Component\EventDispatcher\Event;

class PaginationHelperPlugin extends Plugin
{
    /**
     * @var PaginationHelper
     */
    protected $pagination;

    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return [
            'onPageInitialized' => ['onPageInitialized', 0],
        ];
    }

    /**
     * Enable pagination if page has params.pagination = true.
     */
    public function onPageInitialized()
    {
        /** @var Page $page */
        $page = $this->grav['page'];

        if ($page && ($page->value('header.content.pagination') == 'enabled')) {
            $this->enable([
                'onCollectionProcessed' => ['onCollectionProcessed', 0],
            ]);
        }
    }

    /**
     * Create pagination object for the page.
     *
     * @param Event $event
     */
    public function onCollectionProcessed(Event $event)
    {
        /** @var Collection $collection */
        $collection = $event['collection'];
        $params = $collection->params();

        // Only add pagination if it has been enabled for the collection.
        if (empty($params['pagination'])) {
            return;
        }

        if (!empty($params['limit']) && $collection->count() > $params['limit']) {
            require_once __DIR__ . '/classes/paginationhelper.php';
            $this->pagination = new PaginationHelper($collection);
            $collection->setParams(['pagination' => $this->pagination]);
        }
    }
}