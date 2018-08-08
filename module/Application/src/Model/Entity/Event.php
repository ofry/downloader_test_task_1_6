<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 05.08.18
     * Time: 23:46
     */

    namespace Application\Model\Entity;

    use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
    use Doctrine\ORM\Mapping\ClassMetadata;

    class Event
    {
        const TABLE = 'bills_ru_events';

        private $id;

        private $date;

        private $title;

        private $url;

        public static function loadMetadata(ClassMetadata $metadata)
        {
            $builder = new ClassMetadataBuilder($metadata);
            $builder->setTable(self::TABLE);
            $builder->createField('id', 'integer')->makePrimaryKey()
                ->generatedValue()->build();
            $builder->createField('date', 'date')
                ->nullable(false)->build();
            $builder->createField('title', 'string')->length(230)
                ->nullable(false)->build();
            $builder->createField('url', 'string')->length(240)
                ->unique(true)->nullable(false)->build();
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            !empty($this->id) ? $this->id : null;
        }

        /**
         * @return mixed
         */
        public function getDate()
        {
            if ($this->date instanceof \DateTimeInterface) {
                return $this->date->format('YYYY-MM-DD HH-II-SS');
            }

            return $this->date;
        }

        /**
         * @param mixed $date
         */
        public function setDate($date)
        {
            if ($date === null || $date instanceof \DateTimeInterface) {
                $this->date = $date;
            }
            $tmp = \DateTime::createFromFormat('YYYY-MM-DD HH-II-SS', $date);
            $this->date = (!$tmp) ? null : $tmp;
        }

        /**
         * @return mixed
         */
        public function getTitle()
        {
            return (($this->title !== null) && (!empty(trim($this->title)))) ?
                trim($this->title) : '';
        }

        /**
         * @param mixed $title
         */
        public function setTitle($title)
        {
            $this->title = !empty(trim($title)) ? trim($title) : '';
        }

        /**
         * @return mixed
         */
        public function getUrl()
        {
            return (($this->url !== null) && (!empty(trim($this->url)))) ?
            trim($this->url) : '';
        }

        /**
         * @param mixed $url
         */
        public function setUrl($url)
        {
            $this->url = !empty(trim($url)) ? trim($url) : '';
        }
    }