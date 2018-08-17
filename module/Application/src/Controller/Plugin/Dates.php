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

    class Dates extends AbstractPlugin
    {
        public function createFromString($str, $locale='en')
        {
            DateFullTranslate::setLocale($locale);
            $str = trim($str);
            $date_params = preg_split("/[\s]+/u", $str);
            if (count($date_params) === 2) {
                $year = ' ' . DateFullTranslate::now()->format('Y');
            }
            else {
                $year = '';
            }
            $final_str = implode(' ', $date_params) . $year;
            $date = DateFullTranslate::createFromFormat('j M Y', $final_str)
                ->startOfDay();
            return $date;
        }
    }