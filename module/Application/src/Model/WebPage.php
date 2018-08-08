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

    class WebPage
    {
        private $client_options;
        private $logger;

        public function __construct(Logger $logger, $options = array())
        {
            $this->client_options = $options;
            $this->logger = $logger;
        }

        public function getDom($uri = null)
        {
            $client = new Client($uri, $this->client_options);
            $response = $client->send();
            if ($response->isSuccess()) {
                $result = $response->getBody();
            } else {
                $result = '';
            }
            return $result;
        }
    }