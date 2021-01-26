<?php

class Month{
    public $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    public $months = [' janvier ', ' février ', ' mars ', ' avril ', ' mai ', ' juin ', ' juillet ', ' aôut ', ' septembre ', ' octobre ', ' novembre ', ' décembre '];
    public $month;
    public $year;


    /**
    * @param int $month le mois compris entre 1 et 12
    * @param int l'année
    * @throws Exception
    */

    public function __construct(?int $month = null, ?int $year = null)
    {    
        if ($month === null || $month < 1 || $month > 12){
            $month = intval(date('m'));
        }
        if ($year === null){
            $year = intval(date('Y'));
        }
       
        $this->month = $month;
        $this->year = $year;

    }

    /**
    * renvoie le 1er jour du mois
    * @return \DateTime
    */

    public function getStartingDay (): \DateTime{

        return new \DateTime("{$this->year}-{$this->month}-01");

    }



     /**
    * retourne le mois en toutes lettres 
    * @return string
    */
    public function toString(){

        return $this->months[$this->month - 1] . '' .$this->year;

    }

      /**
    * renvoie le nombre de semaines dans le mois
    * @return string
    */
    public function getWeeks(): int{

        $start = $this->getStartingDay();
        $end = (clone $start)->modify('+1 month -  1 day');
        //var_dump($start, $end);
        //var_dump($end->format('W'), $start->format('W'));
        $weeks = intval($end->format('W')) - intval($start->format('W')) + 1;
        if($weeks < 0){
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }

      /**
    * check si le jour est dans le mois en cours
    * @return bool
    */
    public function withinMonth(\DateTime $date): bool{
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }


      /**
    * renvoie le mois suivant
    */
    public function nextMonth(): Month
    {
        $month = $this->month + 1;
        $year = $this->year;

        if($month > 12){
            $month = 1;
            $year +=1;
        }
        return new  Month($month, $year);

    }

       /**
    * renvoie le mois precedent
    */
    public function previousMonth(): Month
    {
        $month = $this->month - 1;
        $year = $this->year;

        if($month < 1){
            $month = 12;
            $year -=1;
        }
        return new  Month($month, $year);

    }



}

?>