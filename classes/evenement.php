<?php 



class Evenements{
     

      /**
       * recupère les events entre 2dates soit qui commencent entre le 1er et 30 ou 31  du mois en cours
       */
      public function getEventsBetween (\DateTime $start, \DateTime $end): array
      {
         try
          {
          $db = new PDO('mysql:host=localhost;dbname=camping;', 'root', '');
          }
          catch (Exception $e){
          die('Erreur : ' . $e->getMessage());
          }

          $id_user = $_SESSION['user']['id_user'];
          //var_dump($id_user);

          $request_all_events = $db->prepare("SELECT * FROM reservations WHERE id_utilisateur = $id_user AND date_debut BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'");
          //var_dump( $request_all_events);
          $request_all_events->execute();
          $results_resa = ($request_all_events->fetchAll());
          //var_dump( $results_resa);

          return $results_resa;
      }

       /**
       * recupère les events qui commencent pendant le mois en cours indexés par jour
       */
      public function getEventsBetweenByDay (\DateTime $start, \DateTime $end): array
      {
        $events = $this->getEventsBetween($start,$end);
        $days = [];
        foreach($events as $event){
            //var_dump($event);
            $date = explode (' ',$event['date_debut'])[0];
            $fin = explode  (' ',$event['date_fin'])[0];
            //var_dump ($date);

            $date_in = strtotime ($event['date_debut']);
            $date_out = strtotime ($event['date_fin']);

            $test = array();
          

            for($i = $date_in; $i <= $date_out; $i += strtotime('+1 day', 0))
            {
               
              $test[] = date('Y-m-d', $i);

              //$days[date('Y-m-d', $i)] = [$event];

              if(!isset($days[$date])){
                  $days[date('Y-m-d', $i)] = [$event];
              } else {
                  $days[date('Y-m-d', $i)][] = $event;
              }


          }

   
        }
        //echo '<pre>';
        //var_dump($test);
        //echo '</pre>';
        return $days;

      }

        /**
       * recupère un évenement grâce à l'id de la réservation
       */

      public function find (int $id){

        try
        {
        $db = new PDO('mysql:host=localhost;dbname=camping;', 'root', '');
        }
        catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
        }

        $request_infos_resa = $db->prepare("SELECT * FROM reservations WHERE id_reservation = $id");
        //var_dump($request_id);
        $request_infos_resa->execute();
        $result_infos_resa = ($request_infos_resa->fetch());
        //var_dump($result_infos_resa);

        $request_infos_total = $db->prepare("SELECT prix_detail.nb_emplacement, prix_detail.nb_jours, prix_detail.prix_total, detail_lieux.nom_lieu FROM prix_detail JOIN detail_lieux ON prix_detail.id_reservation = detail_lieux.id_reservation WHERE prix_detail.id_reservation = '$id'");
        $request_infos_total->execute();
        $result_infos_total = ($request_infos_total->fetch());

       // var_dump($result_infos_total);

        //if($result_id ===)

        //return $result_infos_resa + $option + $result_infos_total;

        $result =  $result_infos_resa + $result_infos_total;
        //var_dump($result);

        return $result;

     }

     public function option(int $id){

      try
      {
      $db = new PDO('mysql:host=localhost;dbname=camping;', 'root', '');
      }
      catch (Exception $e){
      die('Erreur : ' . $e->getMessage());
      }

      $request_infos_opt = $db->prepare("SELECT nom_option FROM detail_options WHERE id_reservation = $id");
      $request_infos_opt->execute();
      $result_infos_opt = ($request_infos_opt->fetchAll());
      //var_dump($result_infos_opt);

      return $result_infos_opt;

    }

   

    }
       










?>