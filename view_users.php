<?php
  $page_title = "View current users";
  include('./includes/header.html');
  echo '<h1>Registerd Users</h1>';
  // connect to db
  require('./mysql_connect.php');
  // make the query
  $q = "SELECT CONCAT(last_name, ', ', first_name) AS name, DATE_FORMAT(registration_date,
    '%M %d, %Y') AS dr, user_id FROM users ORDER BY registration_date";
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
          <th align="left">Name</th>
          <th align="left">Date Registered</th>
      </tr>
      </thead>
      <tbody>
  ';
    while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
              echo '<tr><td align="lef"><a href="delete_user.php?id='.$row['user_id'].'">Delete</a></td>
                        <td align="left"><a href="edit_user.php?id='.$row['user_id'].'">Edit</a>
                        </td><td align="lef">' . $row['name'] . '</td>
                        <td align="left">' . $row['dr'] .'</td>
                    </tr>';
          };
    echo '</tbody></table>';
    mysqli_free_result($r);
  }else { 
        echo '<p class=”error”>The current users could not be retrieved. We apologize for any
          inconvenience.</p>';
        echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
  }
  mysqli_close($dbc);
  include('includes/footer.html');
?>