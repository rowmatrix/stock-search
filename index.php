<!DOCTYPE html>
<html>
<head>
 <!-- PHP single page web app by Ibar Romay (@github: rowmatrix) (http://ibarromay.com) -->
 <meta charset="UTF-8">
 <meta content='http://ibarromay.com' name='author'>
<title>Stock Search</title>
<style>
.wrapper {
    text-align: center;
    display: table;
    width: 100%;
    height: 100%;
}

.wrapper-center {
    display: table-cell;
    vertical-align: middle;
    margin: 0 auto;
    padding: 0;

}

#search-box {
    display: inline-block;
    background-color: #f5f5f5;
    border: 1px solid #d3d3d3;
    width: 400px;
    padding: 0 10px 25px 10px;
    margin: 0 auto;
}

#results {
    display: block;
    background-color: #f5f5f5;
    border: 2px solid #d3d3d3;
    width: 650px;
    padding: 0;
    margin: 15px auto;
    font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
}

#results table,td,th {
    border: 1px solid #d3d3d3;
}

#results td, th {
    padding: 3px 0;
}

#results table {
    text-align:left;
    width: 100%;
    border-collapse: collapse;
}

#results td {
    background-color: #fbfbfb;
}

hr {
    display: block;
    border: 0;
    height: 1px;
    background-color: #d3d3d3;
    color: #d3d3d3;
    margin: 0 0 15px 0;
}

#buttons {
    margin: 5px 0 5px 160px;
}

h1 {
    padding: 0;
    margin: 5px 0 -5px 0;
    font-weight: 400;
}

img {
    height: 15px;
    width: 15px;
}

</style>
</head>
<body>

<div class="wrapper">
<div class="wrapper-center">
<!-- ==================search box======================== -->
<div id="search-box">    
<h1><em>Stock Search</em></h1>
<hr />
<form method="GET" action="index.php">
<span style="float:left;">Company Name or Symbol: <input id="search" type="text" name="search" required title="Please enter Name or Symbol" value="<?php if(isset($_GET['search'])) echo htmlspecialchars($_GET['search']);?>"><span>


<div id="buttons">
<input id="submit" type="submit" value="Search" name="submit">
<input id="clear" type="button" value="Clear" onclick="clearStock();">
</div>
</form>
<a href="http://www.markit.com/product/markit-on-demand" target="_blank" style="float:right;">Powered by Market on Demand</a>
</div>


<!-- =================php script here=================== -->
<?php
function quoteAPI() {
#Quote API
$json = file_get_contents("http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$_GET['symbol']);
$quote = json_decode($json);

#JSON table

if($quote->Status === "SUCCESS") {
    echo "<div id='results'>";
    echo "<table>";
    echo "<tr><th>Name</th><td style='text-align:center'>".$quote->Name."</td></tr>";
    echo "<tr><th>Symbol</th><td style='text-align:center'>".$quote->Symbol."</td></tr>";
    echo "<tr><th>Last Price</th><td style='text-align:center'>".$quote->LastPrice."</td></tr>";
    echo "<tr><th>Change</th><td style='text-align:center'>".number_format($quote->Change,2,'.','');
    if($quote->Change > 0) {
        echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
    }
    elseif ($quote->Change < 0) {
        echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
    }
    else {
    }
    echo "</td></tr>";
    echo "<tr><th>Change Percent</th><td style='text-align:center'>".number_format($quote->ChangePercent,2,'.','')."&#37;";
    if($quote->ChangePercent > 0) {
        echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
    }
    elseif ($quote->ChangePercent < 0) {
        echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
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
        echo number_format($change_ytd,2,'.','')."<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
    }
    elseif ($change_ytd < 0) {
        echo "&#40;".number_format($change_ytd,2,'.','')."&#41;<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
    }
    else {
        echo number_format($change_ytd,2,'.','');
    }
    echo "</td></tr>";
    echo "<tr><th>Change Percent YTD</th><td style='text-align:center'>".number_format($quote->ChangePercentYTD,2,'.','')."&#37;";
    if($quote->ChangePercentYTD > 0) {
        echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png'>";
    }
    elseif ($quote->ChangePercentYTD < 0) {
        echo "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png'>";
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
    echo "<div id='results' style='padding:4px 0'>There is no stock information available</div>";
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
    echo "<div id='results'>";
    echo "<table>";
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
    echo "<div id='results' style='padding:4px 0'>No Records has been found</div>";
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

</div>
</div>

<!-- =================javascript here=================== -->
<script type="text/javascript">
    var form = document.forms[0], 
        submit = document.getElementById('submit'),
        input = document.getElementById('search'),
        clear = document.getElementById('clear');
    
 
    //search functionality
    input.addEventListener('invalid', function(e) {
        if(input.validity.valueMissing){
            e.target.setCustomValidity("Please enter Name or Symbol"); 
        } else if(!input.validity.valid) {
            e.target.setCustomValidity("This is not a valid Name or Symbol"); 
        } 
        
        input.addEventListener('input', function(e){
            e.target.setCustomValidity('');
        });
    }, false);

    //clear functionality
    function clearStock() {
        search.value = "";
        results.remove();
    }

</script>

</body>
</html>