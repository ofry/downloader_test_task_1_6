<?php
    /**
     * Created by PhpStorm.
     * User: ofryak
     * Date: 07.08.18
     * Time: 3:11
     */

    namespace Application\Controller\Plugin;

    use Zend\Mvc\Controller\Plugin\AbstractPlugin;
    use Jenssegers\Date\Date;

    class Dates extends AbstractPlugin
    {
        public function createFromString($str, $locale='en')
        {
            $str = trim($str);
            $date_params = preg_split("/[\s]+/", $str);
            if (count($date_params) === 2) {
                $year = ' ' . Date::now()->format('Y');
            }
            else {
                $year = '';
            }
            $final_str = implode(' ', $date_params) . $year;
            $date = Date::createFromFormat('j M Y', $final_str)
                ->startOfDay();
            return $date;
        }
    }