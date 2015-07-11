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
    if (isset($_POST['number1']))
    {
        if ($_POST['number1'] <= $_POST['number2'])
            {
                for($i = $_POST['number1']; $i <= $_POST['number2']; $i++)
                {
                    echo "The element with atom number " . $i . " is " . $chemicals[$i][0] . "." . " Its symbol is " . $chemicals[$i][1] . "."
                    . " It is in the element category " . $chemicals[$i][2] . "." .  " Its standard atomic weight is " . $chemicals[$i][3]  . "." . "<br>";
                }
            }
        else
            {
                echo "Start number cannot be greater than end number";  
            }
    }

echo "<form action='Wadhwani_1_3.php' method='post'>
    Please enter the start and end numbers. <br />";
    if (isset($_POST['number1']))
    {
        echo "<select name = 'number1'>";
        for ($i = 1; $i <= 8; $i++)
        {
            if ($i == $_POST['number1'])
            {
                echo "<option selected = 'selected'>" .$i . "</option>";
            }
            else
            {
                echo "<option>" .$i ."</option>";
            }
        }
        echo "</select>";
    }
    else
    {
        echo "<select name='number1'>
        <option selected = 'selected'>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>6</option>
        <option>7</option>
        <option>8</option>
        </select>";
    }
    
   if (isset($_POST['number2']))
    {
        echo "<select name = 'number2'>";
        for ($i = 1; $i <= 8; $i++)
        {
            if ($i == $_POST['number2'])
            {
                echo "<option selected = 'selected'>" .$i . "</option>";
            }
            else
            {
                echo "<option>". $i . "</option>";
            }
        }
        echo "</select>";
    }
    else
    {
        echo "<select name='number2'>
        <option selected = 'selected'>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>6</option>
        <option>7</option>
        <option>8</option>
    </select>";
    }
    echo "<br />
    <input type = 'submit' value = 'Go!'>
    </form>";
?>


