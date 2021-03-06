<?php
  $page_title = "View current users";
  include('./includes/header.html');
  echo '<h1>Registerd Users</h1>';
  // connect to db
  require('./mysql_connect.php');
  $display = 2;
  if(isset($_GET['p']) && is_numeric($_GET['p'])){
    $pages = $_GET['p'];
  }else{
    $q = "SELECT COUNT(user_id) FROM users";
    $r = @mysqli_query($dbc,$q);
    $row = mysqli_fetch_array($r,MYSQLI_NUM);
    $record = $row[0];
    if($record>$display){
      $pages = ceil ($record/$display);
    }else{
      $pages = 1;
    }
  }
  if(isset($_GET['s']) && is_numeric($_GET['s'])){
    $start = $_GET['s'];
  }else{
    $start = 0;
  }
  $sort = isset($_GET['sort'])? $_GET['sort'] : 'rd';
  switch($sort){
    case 'fn':
        $order_by = 'first_name ASC';
        break;
    case 'ln':
        $order_by = 'last_name ASC';
        break;
    case 'rd':
        $order_by = 'registration_date ASC';
        break;
    default:
        $order_by = 'registration_date ASC';
        $sort = 'rd';
        break; 
  };
  // make the query
  $q = "SELECT last_name,first_name , DATE_FORMAT(registration_date,
    '%M %d, %Y') AS dr, user_id FROM users ORDER BY $order_by LIMIT $start,$display";
  $r = @mysqli_query($dbc, $q);  
  $num = mysqli_num_rows($r);
  if($num>0){
    echo '<p>There are '. $num . ' registed users</p>';
  }else{
    echo '<p>There is no registed user</p>';
  }
  if ($r) {
    echo '<table width="50%">
      <thead>
      <tr>
          <th align="left">Delete</th>
          <th align="left">Edit</th>
          <th align="left"><a href="view_users.php?sort=fn">First Name</a></th>
          <th align="left"><a href="view_users.php?sort=ln">Last Name</a></th>
          <th align="left"><a href="view_users.php?sort=rrd">Date Registered</a></th>
      </tr>
      </thead>
      <tbody>
  ';
    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
              echo '<tr><td align="lef"><a href="delete_user.php?id='.$row['user_id'].'">Delete</a></td>
                        <td align="left"><a href="edit_user.php?id='.$row['user_id'].'">Edit</a>
                        </td><td align="left">' . $row['first_name'] . '</td>
                        </td><td align="left">' . $row['last_name'] . '</td>
                        <td align="left">' . $row['dr'] .'</td>
                    </tr>';
          };
    echo '</tbody></table>';
    mysqli_free_result($r);
    if($pages>1){
      echo '<br><p>';
      $current_page = ($start/$display) + 1;
      if($current_page != 1) {
        echo '<a href="view_users.php?s=' . ($start - $display) . '&p=' . $pages .'&sort=' . $sort . '">Previous</a> ';
      }
      for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
          echo '<a href="view_users.php?s=' . (($display * ($i - 1))) . '&p=' . $pages .'&sort=' . $sort . 
              '">' . $i . '</a> ';
        } else {
          echo $i . ' ';
        }
      }
      if ($current_page != $pages) {
        echo '<a href="view_users.php?s=' . ($start + $display) . '&p=' . $pages .'&sort=' . $sort . 
          '">Next</a>';
      }
      echo '</p>';
    }
  }else { 
        echo '<p class=”error”>The current users could not be retrieved. We apologize for any
          inconvenience.</p>';
        echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
  }
  mysqli_close($dbc);
  include('includes/footer.html');
?>