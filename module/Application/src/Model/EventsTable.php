<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 05.08.18
     * Time: 23:44
     */

    namespace Application\Model;

    use Application\Model\Entity\Event;
    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\Tools\SchemaTool;
    use RuntimeException;
    use Zend\Log\Logger;

    class EventsTable
    {
        /**
         * @var \Doctrine\ORM\EntityManager
         */
        private $db;
        /**
         * @var \Zend\Log\Logger                Библиотека для перехвата исключений и ошибок PHP
         */
        private $logger;

        /**
         * TestTable constructor.
         *
         * @param \Doctrine\ORM\EntityManager $db
         * @param \Zend\Log\Logger            $logger
         */
        public function __construct(EntityManager $db, Logger $logger)
        {
            $this->db = $db;
            $this->logger = $logger;
            $this->hydrator = new ClassMethods();
            $this->init();
        }

        private function init()
        {
            /** @var \Doctrine\ORM\Tools\SchemaTool $tool Объект для операций CREATE и DROP */
            $tool = new SchemaTool($this->db);

            /**
             * @var \Doctrine\ORM\Mapping\ClassMetadata[] $classes Данные, на основе которых будут созданы таблицы
             */
            $classes = array(
                $this->db->getClassMetadata(Event::class),
            );
            $tool->dropSchema($classes);
            try {
                $tool->createSchema($classes);
            }
            catch (\Throwable $e) {
                $this->logger->emerg($e);
                throw new RuntimeException('Невозможно создать таблицу в БД.');
            }
        }

        public function insert($element)
        {
            $entry = new Event();
            $entry->setTitle($element['title']);
            $entry->setDate($element['date']);
            $entry->setUrl($element['url']);
            try {
                $this->db->persist($entry);
                $this->db->flush();
            }
            catch (\Throwable $e) {
                $this->logger->emerg($e);
            }
        }

        public function get()
        {
            return $this->db->getRepository(Event::class)->findAll();
        }
    }