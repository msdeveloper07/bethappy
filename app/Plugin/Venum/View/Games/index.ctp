
<?php
  
  $this->Html->script("Venum.gamewindow");

  $this->Html->script("Venum.jquery");
  $this->assign('title', 'Games List');
?>
<table>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php foreach($allGames as $game){ ?>
      <tr>
          <td><?php echo $game['VenumGame']['name'] ?> </td>
          <td><?php echo $game['VenumGame']['type'] ?> </td>
          <td><img src="<?php echo $game['VenumGame']['image'] ?>" style="height: 100px;"> </td>
          <td><a class="btn btn-primary" href="/venum/games/gameview?loadgame=<?php echo $game['VenumGame']['game_id'] ?>&mode=demo&lang=en&technology=<?php echo $game['VenumGame']['type']?>&brand=<?php echo $game['VenumGame']['game_hash']?>" target="_blank">Demo Play</a> 
              <?php 
              if ( isset ( $_SESSION['MyPlayerID'] ) ) 
              { ?>
                <a class="btn btn-primary" href="/venum/games/gameview?loadgame=<?php echo $game['VenumGame']['game_id'] ?>&mode=real&lang=en&technology=<?php echo $game['VenumGame']['type']?>&brand=<?php echo $game['VenumGame']['game_hash']?>&token=" target="_blank">Live Play</a> 
              <?php } ?>
          </td>
      </tr>
    <?php } ?>
</table>

