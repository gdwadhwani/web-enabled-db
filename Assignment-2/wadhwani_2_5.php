<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 7/25/2015
 * Time: 11:26 PM
 */

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'movies_db_733_747');

$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD,
    DB_NAME) OR die ('Could not connect to MySQL: ' .
    mysqli_connect_error() );

if (isset($_POST['actor']) and isset($_POST['director']))
{

    $query = "Select concat(b.d_first_name, ' ', b.d_last_name) as 'Director', concat(a.a_first_name, ' ', a.a_last_name) as 'Actors/Actress', count(*) as 'No of Movies', min(c.year) as 'From Year', max(c.year) as 'To Year'
    FROM actors_actresses as a, directors as b, movies as c, directorship as d, roles as e
    WHERE e.movie_id = d.movie_id And e.act_id = a.act_id AND d.director_id = b.director_id AND e.movie_id = c.movie_id AND d.movie_id = c.movie_id AND a.act_id = '$_POST[actor]' And b.director_id = '$_POST[director]'
    Group By e.act_id, d.director_id";
    $result = mysqli_fetch_array(@mysqli_query ($dbc, $query), MYSQL_ASSOC);
    echo "<div>";
    if ($result != null)
    {
        echo "<p1> Actor/Actress: <b>" . $result['Actors/Actress'] . "</b></p1><br>";
        echo "<p2> Director: <b>" . $result['Director'] . "</b></p2><br>";
        echo "<p3> No of Movies Together: <b>" .$result['No of Movies'] . "</b></p3><br>";
        echo "<p4> From Year: <b>" .$result['From Year']. "</b></p4><br>";
        echo "<p5> To Year: <b>" .$result['To Year']. "</b></p5><br><br>";
    }
    else
    {
        echo "<b>This Pair has never worked together</b><br>";
    }
    echo "</div>";

    $query = "SELECT concat(b.d_first_name, ' ', b.d_last_name) as 'Director', concat(a.a_first_name, ' ', a.a_last_name) as 'Actors/Actress', c.title, c.year, e.role_name
    FROM actors_actresses as a, directors as b, movies as c, directorship as d, roles as e
    WHERE c.movie_id = e.movie_id AND c.movie_id = d.movie_id AND e.act_id = a.act_id AND d.director_id = b.director_id AND a.act_id = '$_POST[actor]' AND b.director_id = '$_POST[director]'
    ORDER By c.year, b.d_last_name, a.a_last_name";
    $result = @mysqli_query ($dbc, $query);
    $total = $result->num_rows;
    if ($total !== 0) {
        echo "Roles in Movies:";
        echo "<div>";
        echo '<table align="left" cellspacing="0" cellpadding="5">
        <tr>
        <td align="left">Movie Title</td>
        <td align="left">Year</td>
        <td align="left">Role</td>
        </tr>';
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
            echo '<tr>
        <td align="left"><b>' . $row['title'] . '</b></td>
        <td align="left"><b>' . $row['year'] . '</b></td>
        <td align="left"><b>' . $row['role_name'] . '</b></td>
        </tr>';
        }
        echo "</table></div><br>";
    }
    else{
    }
}

echo  "<div1 style = \"clear:both; float: left\">
      <form action='Wadhwani_2_5.php' method='post'>
       <p> Please select an actor/actress and a director <br>";
echo "Actor/Actress:
    <select name = 'actor'>";
    $query = "Select act_id, concat(a_first_name, ' ', a_last_name) as 'Actor/Actress' from actors_actresses order by a_first_name";
    $result = @mysqli_query ($dbc, $query);
    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
    {
        if($row['act_id'] == $_POST['actor'])
        {
            echo'<option
            value="'.$row['act_id'].'"
            selected = "selected">'.$row['Actor/Actress'].'
            </option>';
        }
        else
        {
            echo'<option
            value="'.$row['act_id'].'">'.$row['Actor/Actress'].'
        </option>';
        }

    }
    echo "</select>";

echo " Director:
    <select name = 'director'>";
    $query = "Select director_id, concat(d_first_name, ' ', d_last_name) as 'Director' from directors order by d_first_name";
    $result = @mysqli_query ($dbc, $query);
    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
    {
        if($row['director_id'] == $_POST['director'])
        {
            echo'<option
            value="'.$row['director_id'].'"
            selected = "selected">'.$row['Director'].'
            </option>';
        }
        else
        {
            echo'<option
            value="'.$row['director_id'].'">'.$row['Director'].'
            </option>';
        }
    }
echo "</select></p>";

echo "<input type = 'submit' value = 'List Movies and Roles'>
      </form> </div1>"

?>

