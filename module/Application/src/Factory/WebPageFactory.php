<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 05.08.18
     * Time: 9:38
     */

    namespace Application\Factory;

    use Application\Model\WebPage;
    use Zend\ServiceManager\Factory\FactoryInterface;
    use Interop\Container\ContainerInterface;

    class WebPageFactory implements FactoryInterface
    {
        public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
        {
            $logger = $container->get('MyLogger');
            $config = $container->get('config');
            $client_options = isset($config['client']['options']) ?
                $config['client']['options'] : array();
            return new WebPage($logger, $client_options);
        }
    }