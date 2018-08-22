<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 05.08.18
     * Time: 3:25
     */

    namespace Application\Factory;

    use Application\Model\EventsTable;
    use Zend\ServiceManager\Factory\FactoryInterface;
    use Interop\Container\ContainerInterface;
    use Zend\Log\Logger;

    /**
     * Class BaseFactory Фабрика модели таблицы БД
     *
     * @package Application\Factory
     */
    class BaseFactory implements FactoryInterface
    {
        /**
         * @param \Interop\Container\ContainerInterface $container
         * @param string                                $requestedName
         * @param array|null                            $options
         *
         * @return \Application\Model\EventsTable
         */
        public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
        {
            $logger = $container->get('MyLogger');
            Logger::registerErrorHandler($logger);
            Logger::registerExceptionHandler($logger);
            $db = $container->get('doctrine.entitymanager.orm_default');
            return new EventsTable($db, $logger);
        }
    }