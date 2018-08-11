<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 09.08.18
     * Time: 0:14
     */

    namespace Application\Factory;

    use Zend\ServiceManager\Factory\FactoryInterface;
    use Interop\Container\ContainerInterface;

    class DownloaderFactory implements FactoryInterface
    {
        public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
        {
            return $requestedName($container->get(WebPageFactory::class), $container->get(BaseFactory::class));
        }
    }