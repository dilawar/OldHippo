<?php

include_once 'header.php';
include_once 'database.php';
include_once 'tohtml.php';
include_once 'methods.php';
include_once 'check_access_permissions.php';

mustHaveAllOfTheseRoles( array( 'AWS_ADMIN' ) );

?>

<script type="text/javascript" charset="utf-8">
    function showAWSDetails( ) {
        var text = <?php echo json_encode( $aws ) ?>;
       alert( text );
    }
</script>

<?php

/**
    * @brief Put a AWS on this block.
    *
    * @param $awsDays
    * @param $block
    * @param $blockSize
    *
    * @return 
 */
function awsOnThisBlock( $awsDays, $block, $blockSize )
{
    foreach( $awsDays as $awsDay )
    {
        $awsWeek = intval( $awsDay / $blockSize );
        if( 0 == ($block - $awsWeek ) )
            return true;
    }
    return false;
}

function daysToLine( $awsDays, $totalDays, $blockSize = 7)
{
    $today = strtotime( 'now' );
    $totalBlocks = intval( $totalDays / $blockSize ) + 1;
    $line = '<td><small>';


    // These are fixed to 4 weeks (a month).
    $line .= intval( $awsDays[0] / 30.41 ) . ',' ;
    for( $i = 1; $i < count( $awsDays ); $i++ )
        $line .=  intval(( $awsDays[ $i ] - $awsDays[ $i - 1 ] ) / 30.41 ) . ',';

    $line .= "</small></td><td>";

    for( $i = 0; $i <= $totalBlocks; $i++ )
    {
        if( awsOnThisBlock( $awsDays, $i, $blockSize ) )
            $line .= '|';
        else
            $line .= '.';
    }

    $line .= "</td>";
    return $line;
}


// Get AWS in roughly last 5 years.
$numMonths = 60;
if( isset( $_GET['past_months'] ) )
    $numMonths = $_GET[ 'past_months' ];

echo '
    <form method="get" action="">
    Show me AWSs in last 
    <input type="text" name="past_months" value="' . $numMonths . '"> months
    <button name="response" value="Submit">Submit</button>
    </form>
    ';

echo "<h3>Date-wise AWS Summary of last $numMonths months</h3>";

$from = date( 'Y-m-d', strtotime( "- $numMonths months" ) );
$awses = getAWSFromPast( $from );
$datewiseAWS = array( );

// Partition AWSes according to date. Each date should have 3 AWes, ideally.
foreach( $awses as $aws )
{
    if( ! array_key_exists( $aws['date'], $datewiseAWS ) )
        $datewiseAWS[ $aws['date'] ] = array( $aws );
    else
        array_push( $datewiseAWS[ $aws['date'] ], $aws );
}

$table = '<table border="0" class="show_aws_summary">';

$i = 0;
foreach( $datewiseAWS as $date => $awses )
{
    $i +=1 ;
    $printableDate = date( 'M d, Y', strtotime($date) ); 
    $table .= "<tr> <td>$i</td> <td><small> " . $printableDate .  "</small></td>";
    foreach( $awses as $aws )
    {
        $speaker = $aws[ 'speaker' ];
        $column = loginToText( $speaker ) . "<br><small> $speaker </small>";
        $column .= "<br><small>" . $aws[ 'supervisor_1' ] . "</small>";
        //$column .= "<p><small>" . $aws[ 'title' ] . "</small></p>";
        //$table .= '<td><button onClick="showAWSDetails()">'.$column .'</button>';
        $table .= "<td> $column </td>";
    }
    $table .= "</tr>";
}

$table .= "</table>";
echo $table;

echo goBackToPageLink( "admin_aws.php", "Go back" );

?>
