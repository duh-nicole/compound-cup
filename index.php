<?php 
    //set default value for initial page load
    $investment = $_POST['investmentAmount'] ?? '10000';
    $interest_rate = $_POST['yearlyInterestRate'] ?? '5';
    $years = $_POST['years'] ?? '5';
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Future Value Calculator</title>
    <link rel="stylesheet" href="main.css"/>
</head>

<body>
    <main>
    <h1>Future Value Calculator</h1>
    <?php if (!empty($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } 
    ?>
    <form action="display_results.php" method="post">

        <div id="data">
            <label for="investmentAmount">Investment Amount:</label>
            <select id="investmentAmount" name="investmentAmount">
                <?php
                // The loop for the Investment Amount
                // increasing by 5000
                for ($i = 10000; $i <= 50000; $i +=5000) {
                    $displayValue = number_format($i);
                    $selected = ($i == $investment) ? 'selected="selected"' : '';
                    echo "<option value=\"$i\" $selected>$$displayValue</option>";
                }
                ?>
            </select>
            <br>

            <label for="yearlyInterestRate">Yearly Interest Rate:</label>
            <select id="yearlyInterestRate" name="yearlyInterestRate">
                <?php
                // The loop for Yearly Interest Rate
                // increasing by .5
                for ($i = 4.0; $i <= 12.0; $i += 0.5) {
                    $displayValue = number_format($i, 1);
                    $selected = ($i == $interest_rate) ? 'selected="selected"' : '';
                    echo "<option value=\"$i\" $selected>$displayValue%</option>";
                }
                ?>
            </select>
            <br>

            <label for="numberYears">Number of Years:</label>
            <input type="number" name="years" id="numberYears"
                value="<?php echo htmlspecialchars($years); ?>"/>
            <br>
        </div>

        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Calculate"/><br>
        </div>
    </form>
    </main>
</body>
</html>