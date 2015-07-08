<?php
    $chemicals = array(
        1 => array("Hydrogen", "H", "Diatomic nonmetals", 1.01),
        2 => array("Helium", "He", "Noble gases", 4),
        3 => array("Lithium", "Li", "Alkali metals", 6.94),
        4 => array("Beryllium", "Be", "Alkaline earth metals", 9.01),
        5 => array("Boron", "B", "Metalloids", 10.81),
        6 => array("Carbon", "C", "Polyatomic nonmetals", 12.01),
        7 => array("Nitrogen", "N", "Diatomic nonmetals", 14.01),
        8 => array("Oxygen", "O", "Diatomic nonmetals", 16.00)
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
                    echo "The element with atom number " . $_POST['number'] . " is " . $chemicals[$_POST['number']][0] . "." . " Its symbol is " . $chemicals[$_POST['number']][1] . "."
                    . " It is in the element category " . $chemicals[$_POST['number']][2] . "." .  " Its standard atomic weight is " . $chemicals[$_POST['number']][3]  . ".";
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

echo "<form action='Wadhwani_1_2.php' method='post'>
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


