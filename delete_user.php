  <?php
  if(isset($_GET['id'] )&& is_numeric($_GET['id'])){
    $id = $_GET['id'];
  }else if(isset($_POST['id']) && is_numeric($_POST['id'])){
    $id = $_GET['id'];
  }else{
    echo '<p>Some thing goes wrong</p>';
    include('./includes/footer.html');
  }
  require('./mysql_connect.php');
  if($_SERVER['REQUEST_METHOD']=='POST'){
    if($_POST['sure']=='yes'){
      $q = "DELETE FROM users WHERE user_id =$id LIMIT 1";
      $r = @mysqli_query($dbc,$q);
      if(mysqli_affected_rows($dbc)==1){
        echo '<p>User is deleted</p>';
      }else{
        echo '<p>Some thing goes wrong</p>';
        echo '<p>' . mysqli_error($dbc) . '<br>Query: ' . $q . '</p>';
      }
    }else{
      echo '<p>User have not been deleted</p>';
    }
  }else{
    $q="SELECT CONCAT(last_name,',',first_name) FROM users WHERE user_id= $id";
    $r=@mysqli_query($dbc,$q);
    if(mysqli_num_rows($r)==1){
      $row = mysqli_fetch_array($r,MYSQLI_NUM);
      echo "<h3>$row[0]</h3><p>Are you sure you want to delete this user?</p>";
      echo '<form action="delete_user.php" method="post">
        <input type="radio" name="sure" value="Yes"> Yes
        <input type="radio" name="sure" value="No" checked="checked"> No
        <input type="submit" name="submit" value="Submit">
        <input type="hidden" name="id" value="' . $id . '">
      </form>';
    }else{
      echo '<p>Something goes wrong'.mysqli_error($dbc).'</p>';
    }
  }
  mysqli_close($dbc);
  include('./includes/footer.html');
?>