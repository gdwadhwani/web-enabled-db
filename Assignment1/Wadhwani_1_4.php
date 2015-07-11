<?php
    
    if (isset($_POST['degree']))
    {
        if ($_POST['degree'] == "")
        {
            echo "You submitted an empty form.";
        }
        else
        {
            if (is_numeric($_POST['degree']))
            {
                if ($_POST['degree'] >= 0 && $_POST['degree'] <= 90)
                {
                    $answer = math($_POST['sign'], $_POST['degree']);
                    if ($_POST['sign'] == "Cos")
                    {
                        echo "The Cosine of " . $_POST['degree'] . "° is " . $answer;
                    }
                    elseif($_POST['sign'] == "Sin")
                    {
                        echo "The Sine of " . $_POST['degree'] . "° is " . $answer;
                    }
                    else
                    {
                        echo "The Tangent of " . $_POST['degree']  . "° is " . $answer;
                    }
                }
                else
                {
                    echo "The number you entered is not between 0 and 90";  
                }
            }
            else
            {
                echo "Only Numeric values please";
            }
        }
    }
    function math($sign, $degree)
    {
        $angle = deg2rad($degree);
        switch($sign)
        {
            case "Sin":
                return sin($angle);
                break;
            case "Cos":
                return cos($angle);
                break;
            case "Tan":
                return tan($angle);
                break;
        }
    }
    
    echo "<form action='Wadhwani_1_4.php' method='post'>
    Please enter the degrees between 0 and 90 <br>";
    if (isset($_POST['sign']))
    {
        echo "<select name = 'sign'>";
        if ($_POST['sign'] == 'Sin')
        {
            echo "<option selected = 'selected'>Sin</option>
            <option>Cos</option>
            <option>Tan</option>";
        }
        elseif($_POST['sign'] == 'Cos')
        {
            echo "<option>Sin </option>
            <option selected = 'selected'>Cos</option>
            <option>Tan</option>";
        }
        elseif($_POST['sign'] == 'Tan')
        {
            echo "<option>Sin</option>
            <option>Cos</option>
            <option selected = 'selected'>Tan</option>";
        }
        echo "</select>";
    }
    else
    {
        echo "<select name='sign'>
        <option selected = 'selected'>Sin</option>
        <option>Cos</option>
        <option>Tan</option>
        </select>";
    }
    
    echo "<input type='text' name='degree' size= '5' value = '";
    if (isset($_POST['degree']))
    {
        echo $_POST['degree'];
    }
    echo "'>°
    <input type = 'submit' value = 'Go!'>
    </form>"
?>