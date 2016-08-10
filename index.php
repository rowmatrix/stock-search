<!DOCTYPE html>
<html lang="en">
<head>
    <!-- PHP web app by Ibar Romay (http://ibarromay.com) -->
    <title>Stock Market Search</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='http://ibarromay.com' name='author'>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>

    <div class="container">
    <!-- <div class="wrapper-center"> -->
    <!-- ==================search box======================== -->
    <!-- <div id="search-box"> --> 
    <div class="jumbotron"> 
    <h1>Stock Search</h1>
    <hr />
    <form method="GET" action="index.php" class="form-inline">
        <div class="form-group has-feedback">
            <label class="control-label" for="search">Company Name or Symbol:</label>
            <input class="form-control" id="search" type="text" name="search" required title="Please enter Name or Symbol" value="<?php if(isset($_GET['search'])) echo htmlspecialchars($_GET['search']);?>">
            <i class="form-control-feedback glyphicon glyphicon-search"></i>
        </div>
        <input class="btn btn-primary" id="submit" type="submit" value="Search" name="submit">
        <input class="btn btn-info" id="clear" type="button" value="Clear" onclick="clearStock();">
    </form>
    <a href="http://www.markit.com/product/markit-on-demand" target="_blank" >Powered by Market on Demand</a>
    </div>


    <!-- =================php script here=================== -->
    <?php
    function quoteAPI() {
    #Quote API
    $json = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$_GET['symbol']);
    $quote = json_decode($json);

    #JSON table

    if($quote->Status === "SUCCESS") {
        echo "<div id='results' class='panel panel-primary'>";
        echo "<table class='table'>";
        echo "<tr><th>Name</th><td style='text-align:center'>".$quote->Name."</td></tr>";
        echo "<tr><th>Symbol</th><td style='text-align:center'>".$quote->Symbol."</td></tr>";
        echo "<tr><th>Last Price</th><td style='text-align:center'>".$quote->LastPrice."</td></tr>";
        echo "<tr><th>Change</th><td style='text-align:center'>".number_format($quote->Change,2,'.','');
        if($quote->Change > 0) {
            //echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
            echo "<span class='glyphicon glyphicon-arrow-up text-success' aria-hidden='true'></span>";
        }
        elseif ($quote->Change < 0) {
            //echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
            echo "<span class='glyphicon glyphicon-arrow-down text-danger' aria-hidden='true'></span>";
        }
        else {
        }
        echo "</td></tr>";
        echo "<tr><th>Change Percent</th><td style='text-align:center'>".number_format($quote->ChangePercent,2,'.','')."&#37;";
        if($quote->ChangePercent > 0) {
            //echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
            echo "<span class='glyphicon glyphicon-arrow-up text-success' aria-hidden='true'></span>";
        }
        elseif ($quote->ChangePercent < 0) {
            //echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
            echo "<span class='glyphicon glyphicon-arrow-down text-danger' aria-hidden='true'></span>";
        }
        else {
        }
        echo "</td></tr>";
        echo "<tr><th>Timestamp</th><td style='text-align:center'>";
        $date = date_create($quote->Timestamp);  #local time = 'America/Los_Angeles')
        date_timezone_set($date, timezone_open('America/New_York'));
        echo date_format($date, 'Y-m-d h:i A T')."</td></tr>";
        echo "<tr><th>Market Cap</th><td style='text-align:center'>";
        $market_cap = $quote->MarketCap / 1000000000;
        if ($market_cap < 0.01) {
            echo number_format($market_cap*1000,2,'.','')." M";
        }
        else {
            echo number_format($market_cap,2,'.','')." B";
        }
        echo "</td></tr>";
        echo "<tr><th>Volume</th><td style='text-align:center'>".number_format($quote->Volume)."</td></tr>";
        echo "<tr><th>Change YTD</th><td style='text-align:center'>";
        $change_ytd = $quote->LastPrice - $quote->ChangeYTD;
        if($change_ytd > 0) {
            echo number_format($change_ytd,2,'.','')."<span class='glyphicon glyphicon-arrow-up text-success' aria-hidden='true'></span>";
        }
        elseif ($change_ytd < 0) {
            echo "&#40;".number_format($change_ytd,2,'.','')."&#41;<span class='glyphicon glyphicon-arrow-down text-danger' aria-hidden='true'></span>";
        }
        else {
            echo number_format($change_ytd,2,'.','');
        }
        echo "</td></tr>";
        echo "<tr><th>Change Percent YTD</th><td style='text-align:center'>".number_format($quote->ChangePercentYTD,2,'.','')."&#37;";
        if($quote->ChangePercentYTD > 0) {
            //echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
            echo "<span class='glyphicon glyphicon-arrow-up text-success' aria-hidden='true'></span>";
        }
        elseif ($quote->ChangePercentYTD < 0) {
            //echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
            echo "<span class='glyphicon glyphicon-arrow-down text-danger' aria-hidden='true'></span>";
        }
        else {
        }
        echo "</td></tr>";
        echo "<tr><th>High</th><td style='text-align:center'>".$quote->High."</td></tr>";
        echo "<tr><th>Low</th><td style='text-align:center'>".$quote->Low."</td></tr>";
        echo "<tr><th>Open</th><td style='text-align:center'>".$quote->Open."</td></tr>";
        echo "</table>";
        echo "</div>";
    }
    else {
        echo "<div id='results' class='panel panel-default'><div class='panel-heading'>There is no stock information available</div></div>";
    }


    }#end quoteAPI()

    function search() {
    #Lookup API
    $url = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/xml?input=".$_GET['search'];
    #simpleXML
    #$sxml = simplexml_load_file($url) or die("<div id='results' style='padding:4px 0'>No Records has been found</div>");
    $sxml = @simplexml_load_file($url);
    if($sxml) {
        #LookupResultList table
        echo "<div id='results' class='panel panel-primary'>";
        //Nodfecho "<div class='panel-heading'></div>";
        echo "<table class='table'>";
        echo "<tr><th>Name</th><th>Symbol</th><th>Exchange</th><th>Details</th>";
        foreach ($sxml->children() as $LookupResult) {
            echo "<tr><td>".$LookupResult->Name."</td>";
            echo "<td>".$LookupResult->Symbol."</td>";
            echo "<td>".$LookupResult->Exchange."</td>";
            echo "<td><a href='index.php?search=".$_GET['search']."&amp;func=quoteAPI&amp;symbol=".$LookupResult->Symbol."'>More Info</a></td></tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    else {
        echo "<div id='results' class='panel panel-default'><div class='panel-heading'>No such record has been found</div></div>";
    }

    }#end search()


    #on Search button
    if(isset($_GET['submit'])) {
        search();    
    }

    #on More Info link
    if(isset($_GET['func']) && $_GET['func'] === "quoteAPI") {
        quoteAPI();
    }
    ?>

    <!-- </div> -->
    </div>

    <!-- ===========javascript========== -->
    <script src="script.js"></script>
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    

</body>
</html>