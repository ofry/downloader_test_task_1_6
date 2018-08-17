<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 06.08.18
     * Time: 21:13
     */

    namespace Application\Controller;

    use Zend\Dom\Document;
    use Zend\Mvc\Controller\AbstractRestfulController;
    use Application\Model\WebPage;
    use Application\Model\EventsTable;
    use Zend\Dom\Document\Query;
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
            /**
             * @var \Zend\Dom\Document
             */
            $document = $this->dom()->createFrom($this->page->getDom(self::URI));
            $encoding = $document->getDomDocument()->encoding;
            $events = Query::execute('tr.bizon_api_news_row', $document, Query::TYPE_CSS);
            foreach ($events as $event)
            {
//                $event_dom = new \DOMDocument();
//                $event_dom->loadXML(simplexml_import_dom($event)->asXML());
//                $event_dom->encoding = $encoding;
//                $event_document = new Document($event_dom->saveXML());
                $event_document = $this->dom()->createFrom($event, $encoding);
                $event_date = Query::execute('td.news_date', $event_document, Query::TYPE_CSS);
                $event_link = Query::execute('td > a', $event_document, Query::TYPE_CSS);
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