<?php 

$page_selected = 'planning';

?>

<!DOCTYPE html>
<html>
<head>
  <title>camping - planning </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" type="image/x-icon" href="css/images/logo1.jpg">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/style1.css">
  <link rel="stylesheet" type="text/css" href="css/style2.css">
</head>
<body>
  <header>
    <?php include("includes/header.php") ?>
  </header>
  <main>
    <?php 

    require 'classes/mois.php';
    require 'classes/evenement.php';
  
    
    $month = new Month($_GET['month'] ?? null, $GET['year'] ?? null);
    $start  = $month->getStartingDay();
    $start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
    $weeks = $month->getWeeks();
    $end = (clone $start)->modify('+'. (6 + 7 * ($weeks -1)) .'days');
    //var_dump($end);
    ?>
    
    <section id="planning-nav">
      <p><?= $month->toString();?></p>
      <?php if (isset($_SESSION['user'])){ 
      $events = new Evenements();
      $events =  $events->getEventsBetweenByDay($start,$end);
      //echo '<pre>';
      //var_dump($events);
       //echo '</pre>';?>
      <h1>vos réservations</h1>
      <?php } else {?>
      <h1>réservez votre séjour maintenant</h1>
      <?php } ?>
      <div id="next-button">
        <a href="planning.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-light">&lt;</a>
        <a href="planning.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-light">&gt;</a>
      </div>
    </section>

    <section id="container-planning">
      <section id="box-button">
        <button class="icon-btn add-btn">
          <div class="add-icon"></div>
          <a href="reservation-form.php"><div class="btn-txt">nouvelle réservation</div></a>
        </button>
      </section>
   
      <?php $month->getWeeks();?>
      <table class="calendar_table calendar_table--<?=$weeks;?>weeks">
        <?php for($i = 0; $i < $weeks; $i++): ?>
        <tr>
        <?php foreach($month->days as $key =>$day):
          $date = (clone $start)->modify("+" . ($key + $i * 7) . "days");
          $eventsForDay = $events[$date->format('Y-m-d')] ?? [];
          //var_dump($eventsForDay);
        ?>
          <td class="<?= $month->withinMonth($date)?'':'calendar-other'; ?>">
            <?php if ($i == 0): ?>
            <div class="calendar-weekday"><?= $day ?></div>
            <?php endif; ?>

            <div class="calendar-day"><a href="reservation-form.php"><?= $date->format('d');?></a></div>
            <?php if (isset($_SESSION['user'])){ ?>

              <?php foreach($eventsForDay as $event): ?>
              <div class="calendar-event">
                <p id="resa"><?= (new DateTime($event['date_debut']))->format('d-m'); (new DateTime($event['date_fin']))->format('d-m');?></p><a href="reservation.php?id=<?=$event['id_reservation'];?>"><p> -vous avez une réservation à cette date N° <?= $event['id_reservation'];?>-</p>
              </div>
              <a href="reservation.php?id=<?=$event['id_reservation'];?>"><p id="puce">⚪</p></a>
              <?php endforeach; ?>
            <?php } ?>
          </td>
        <?php endforeach; ?>
        </tr>
        <?php endfor; ?>
      </table>

    </section>
  </main>
  <footer>
    <?php include("includes/footer.php");?>
  </footer>
</body>
</html>