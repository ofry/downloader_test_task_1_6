<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 17.08.18
     * Time: 16:18
     */

    namespace Application\Controller\Plugin;

    use Zend\Mvc\Controller\Plugin\AbstractPlugin;
    use Zend\Dom\Document;

    /**
     * Class DomExtender
     *
     * @package Application\Controller\Plugin
     */
    class DomExtender extends AbstractPlugin
    {
        /**
         * @param string|\DOMElement $data
         * @param string|null $encoding
         *
         * @return \Zend\Dom\Document
         */
        public function createFrom($data, $encoding = null)
        {
            if (is_string($data)) {
                return new Document($data);
            }
            else if ($data instanceof \DOMElement) {
                /**
                 * @var string
                 */
                $xml = simplexml_import_dom($data)->asXML();
                $dom = new \DOMDocument();
                $dom->loadXML($xml);
                if ($encoding !== null) {
                    $dom->encoding = $encoding;
                }
                return new Document($dom->saveXML());
            }
            else {
                throw new \InvalidArgumentException("Параметр data должен быть строкой или объектом DomElement.");
            }
        }
    }