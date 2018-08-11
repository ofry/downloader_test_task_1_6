<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 06.08.18
     * Time: 21:13
     */

    namespace Application\Controller;

    use Zend\Mvc\Controller\AbstractRestfulController;
    use Application\Model\WebPage;
    use Application\Model\EventsTable;
    use Zend\Dom\Query;
    use Zend\Hydrator\ClassMethods;
    use Zend\View\Model\JsonModel;

    class DownloaderController extends AbstractRestfulController
    {
        const URI = 'https://www.bills.ru/';

        private $page;

        private $table;

        private $hydrator;

        public function __construct(WebPage $page, EventsTable $table)
        {
            $this->page = $page;
            $this->table = $table;
            $this->hydrator = new ClassMethods();
        }

        public function indexAction()
        {
            $dom = new Query($this->page->getDom(self::URI));
            $events = $dom->execute('tr.bizon_api_news_row');
            foreach ($events as $event)
            {
                $event_html = new Query(simplexml_import_dom($event)->asXML());

                $event_date = $event_html->execute('td.news_date');
                $event_link = $event_html->execute('td > a');
                $event_date->rewind();
                $event_link->rewind();

                $entryData = array();
                $entryData['date'] = $event_date->valid() ?
                    $this->dates()->createFromString(trim($event_date->current()->textContent), 'ru'): '';
                $entryData['title'] = $event_link->valid() ?
                    trim($event_link->current()->textContent) : '';
                $entryData['url'] = $event_link->valid() ?
                    trim($event_link->current()->attributes->
                        getNamedItem('href')->nodeValue) : '';

                $this->table->insert($entryData);
            }

            //debug
            $entries = $this->table->get();
            $result = array();
            foreach ($entries as $entry) {
                $result[] = $this->hydrator->extract($entry);
            }
            return new JsonModel(array('response' => $result));
        }
    }