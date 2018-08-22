<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 07.08.18
     * Time: 3:11
     */

    namespace Application\Controller\Plugin;

    use ofry\DateFullTranslate\DateFullTranslate;
    use Zend\Mvc\Controller\Plugin\AbstractPlugin;

    /**
     * Class Dates
     *
     * @package Application\Controller\Plugin
     */
    class Dates extends AbstractPlugin
    {
        /**
         * @param string $str Строка, содержащая дату в формате "j M" или "j M Y"
         * @param string $locale используемый в строке язык
         *
         * @return \ofry\DateFullTranslate\DateFullTranslate
         */
        public function createFromString($str, $locale='en')
        {
            DateFullTranslate::setLocale($locale);
            $str = trim($str);
            /**
             * @var string[] компоненты даты
             */
            $date_params = preg_split("/[\s]+/u", $str);
            if (count($date_params) === 2) {
                $year = ' ' . DateFullTranslate::now()->format('Y');
            }
            else {
                $year = '';
            }
            /**
             * @var string
             */
            $final_str = implode(' ', $date_params) . $year;
            $date = DateFullTranslate::createFromFormat('j M Y', $final_str)
                ->startOfDay();
            return $date;
        }
    }