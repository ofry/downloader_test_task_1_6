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

    /**
     * Class DownloaderController
     *
     * @package Application\Controller
     */
    class DownloaderController extends AbstractRestfulController
    {
        /**
         * URL страницы, которую мы парсим
         */
        const URI = 'https://www.bills.ru/';

        /**
         * @var \Application\Model\WebPage page
         */
        private $page;

        /**
         * @var \Application\Model\EventsTable table
         */
        private $table;

        /**
         * @var \Zend\Hydrator\ClassMethods hydrator
         */
        private $hydrator;

        /**
         * DownloaderController constructor.
         *
         * @param \Application\Model\WebPage     $page
         * @param \Application\Model\EventsTable $table
         */
        public function __construct(WebPage $page, EventsTable $table)
        {
            $this->page = $page;
            $this->table = $table;
            $this->hydrator = new ClassMethods();
        }

        /**
         * @return \Zend\View\Model\JsonModel
         */
        public function indexAction()
        {
            /**
             * @var \Zend\Dom\Document
             */
            $document = $this->dom()->createFrom($this->page->getDom(self::URI));
            /**
             * @var string|null
             */
            $encoding = $document->getDomDocument()->encoding;
            $events = Query::execute('tr.bizon_api_news_row', $document, Query::TYPE_CSS);
            /**
             * @var \DOMElement
             */
            foreach ($events as $event)
            {
                /**
                 * @var \Zend\Dom\Document
                 */
                $event_document = $this->dom()->createFrom($event, $encoding);
                $event_date = Query::execute('td.news_date', $event_document, Query::TYPE_CSS);
                $event_link = Query::execute('td > a', $event_document, Query::TYPE_CSS);
                $event_date->rewind();
                $event_link->rewind();

                $entryData = array();
                /**
                 * @var string
                 */
                $entryData['date'] = $event_date->valid() ?
                    $this->dates()->createFromString(trim($event_date->current()->textContent), 'ru'): '';
                /**
                 * @var string
                 */
                $entryData['title'] = $event_link->valid() ?
                    trim($event_link->current()->textContent) : '';
                /**
                 * @var string
                 */
                $entryData['url'] = $event_link->valid() ?
                    trim($event_link->current()->attributes->
                        getNamedItem('href')->nodeValue) : '';

                $this->table->insert($entryData);
            }

            //debug code
            $entries = $this->table->get();
            $result = array();
            foreach ($entries as $entry) {
                /**
                 * @var object
                 */
                $result[] = $this->hydrator->extract($entry);
            }
            return new JsonModel(array('response' => $result));
        }
    }