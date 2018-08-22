<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 05.08.18
     * Time: 9:12
     */

    namespace Application\Model;

    use Zend\Http\Client;
    use Zend\Log\Logger;

    /**
     * Class WebPage
     *
     * @package Application\Model
     */
    class WebPage
    {
        /**
         * @var array
         */
        private $client_options;
        /**
         * @var \Zend\Log\Logger
         */
        private $logger;

        /**
         * WebPage constructor.
         *
         * @param \Zend\Log\Logger $logger
         * @param array            $options
         */
        public function __construct(Logger $logger, $options = array())
        {
            $this->client_options = $options;
            $this->logger = $logger;
        }

        /**
         * Возвращает body запроса ресурса $uri
         *
         * @param string|null $uri адрес ресурса
         *
         * @return string
         */
        public function getDom($uri = null)
        {
            /**
             * @var \Zend\Http\Client
             */
            $client = new Client($uri, $this->client_options);
            /**
             * @var \Zend\Http\Response полученный ответ
             */
            $response = $client->send();
            if ($response->isSuccess()) {
                $result = $response->getBody();
            } else {
                $result = '';
            }
            return $result;
        }
    }