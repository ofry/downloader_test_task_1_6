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

    /**
     * Class DownloaderFactory Фабрика контроллера, реализующего парсинг
     *
     * @package Application\Factory
     */
    class DownloaderFactory implements FactoryInterface
    {
        /**
         * @param \Interop\Container\ContainerInterface $container
         * @param string                                $requestedName
         * @param array|null                            $options
         *
         * @return object
         */
        public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
        {
            return new $requestedName($container->get(WebPageFactory::class), $container->get(BaseFactory::class));
        }
    }