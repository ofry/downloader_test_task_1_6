<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 06.08.18
     * Time: 21:13
     */

    namespace Application\Controller;

    use Zend\Mvc\Controller\AbstractActionController;
    use Application\Model\WebPage;
    use Application\Model\EventsTable;
    use Zend\Dom\Query;

    class DownloaderController extends AbstractActionController
    {
        const URI = 'https://www.bills.ru/';

        private $page;

        private $table;

        public function __construct(WebPage $page, EventsTable $table)
        {
            $this->page = $page;
            $this->table = $table;
        }

        public function indexAction()
        {
            $dom = new Query($this->page->getDom(self::URI));
            $events = $dom->execute('tr.bizon_api_news_row');
            foreach ($events as $event)
            {
                $event_html = new Query($event->textContent);
                $event_date = $event_html->execute('td.news_date');
                $event_link = $event_html->execute('td > a');
                $event_date->rewind();
                $event_link->rewind();

                $entry = array();
                $entry['date'] = $event_date->valid() ?
                    trim($event_date->current()->textContent): '';
                $entry['title'] = $event_link->valid() ?
                    trim($event_link->current()->textContent) : '';
                $entry['url'] = $event_link->valid() ?
                    trim($event_link->current()->attributes->
                        getNamedItem('href')->nodeValue) : '';
            }
        }
    }