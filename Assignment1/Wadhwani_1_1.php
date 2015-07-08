<?php
    $chemicals = array(
        1 => "Hydrogen",
        2 => "Helium",
        3 => "Lithium",
        4 => "Beryllium",
        5 => "Boron",
        6 => "Carbon",
        7 => "Nitrogen",
        8 => "Oxygen"
    );
    if (isset($_POST['number']))
    {
        if (empty($_POST['number']))
        {
            echo "You submitted an empty form.";
        }
        else
        {
            if (is_numeric($_POST['number']))
            {
                if ($_POST['number'] > 0 && $_POST['number'] <= 8)
                {
                    echo "The element with atom number " . $_POST['number'] . " is " . $chemicals[$_POST['number']];
                }
                else
                {
                    echo "The number you entered is not between 1 and 8";  
                }
            }
            else
            {
                echo "Only Numeric values please";
            }
        }
    }

echo "<form action='Wadhwani_1_1.php' method='post'>
    Please enter a number between 1 and 8. <br />
    <input type='text' name='number' size= '10' value = '";
    if (isset($_POST['number']))
    {
        echo $_POST['number'];
    }
    echo "'>
    <input type = 'submit' value = 'Go!'>
    </form>"
?>


