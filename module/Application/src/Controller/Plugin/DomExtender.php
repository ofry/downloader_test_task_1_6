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

    class DomExtender extends AbstractPlugin
    {
        public function createFrom($data, $encoding = null)
        {
            if (is_string($data)) {
                return new Document($data);
            }
            else if ($data instanceof \DOMElement) {
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