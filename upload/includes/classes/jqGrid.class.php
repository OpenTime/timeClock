<?php
/**
 * Time Clock
 * jqGrid
 *
 * @author      MarQuis L. Knox <opensource@marquisknox.com>
 * @license     GNU Affero General Public License v3 (AGPL-3.0)
 * @link        http://www.gnu.org/licenses/agpl-3.0.html
 * @link        https://github.com/MarQuisKnox/timeClock 
 *
 * @since       Saturday, February 26, 2011 / 12:33 PM GMT+1 mknox
 * @edited      $Date: 2011-05-14 18:04:46 +0200 (Sat, 14 May 2011) $ $Author: mknox $
 * @version     $Revision: 5 $
 *
 * @package     Time Clock
 * @subpackage  jqGrid
 */

class jqGrid
{
    private $_timeClock;

    function __construct()
    {
        global $timeClock;
        $this->_timeClock = $timeClock;
    }

    function fetchAllHours()
    {
        $sql = "SELECT * FROM `records_recent_first`";
        $res = mysql_query( $sql ) OR die( mysql_error() );

        if( mysql_num_rows( $res ) > 0 ) {
            $data = array();
            while( $row = mysql_fetch_assoc( $res ) ) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function rowCount()
    {
        $sql = "SELECT `Id` FROM `records_recent_first` ";
        $res = mysql_query( $sql ) OR die( mysql_error() );

        return mysql_num_rows( $res );
    }

    function output_json()
    {
        // normal work days
        $workDays = explode( ',', requiredWorkDays );

        if( empty( $_POST ) ) {
            return;
        }

        // get the requested page
        $page = $_POST['page'];
        // get how many rows we want to have into the grid
        // rowNum parameter in the grid
        $limit  = $_POST['rows'];
        // get index row - i.e. user click to sort
        // at first time sortname parameter - after that the index from colModel
        $sidx   = $_POST['sidx'];
        // sorting order - at first time sortorder
        $sord   = $_POST['sord'];
        $sidx   = ( !$sidx ) ? ' , 1 ' : ' , '.$sidx;

        // calculate the number of rows for the query. We need this to paging the result
        $count  = $this->rowCount();

        // calculation of total pages for the query
        if($count > 0) {
	        $total_pages = ceil( $count / $limit );
        } else {
            $total_pages = 0;
        }

        // if for some reasons the requested page is greater than the total
        // set the requested page to total page
        if ( $page > $total_pages ) {
            $page = $total_pages;
        }

        // calculate the starting position of the rows
        $start = ( $limit * $page ) - $limit; // do not put $limit*($page - 1)
        // if for some reasons start position is negative set it to 0
        // typical case is that the user type 0 for the requested page
        if( $start < 0 ) {
            $start = 0;
        }

        // the actual query for the grid data
        $sql    = "SELECT * FROM `records_recent_first` ";
        $sql   .= "ORDER BY `inTimestamp` DESC ".mysql_real_escape_string( $sidx )." ".mysql_real_escape_string( $sord )." ";
        $sql   .= "LIMIT ".mysql_real_escape_string( $start ).", ".mysql_real_escape_string( $limit );

        $res    = mysql_query( $sql ) OR die( mysql_error() );

        $count  = mysql_num_rows( $res );
        if( $count > 0 ) {
            $data = array();
            while( $row = mysql_fetch_assoc( $res ) ) {
                $data[] = $row;
            }
        }

        for ( $i = 0; $i < $count; $i++ ) {
            // START:   check for multiple clock-in events
            $sql    = "SELECT * FROM `records_recent_first` ";
            $sql   .= "WHERE `inTimestamp` < '".mysql_real_escape_string( $data[$i]['inTimestamp'] )."' ";
            $sql   .= "AND `month` = '".mysql_real_escape_string( $data[$i]['month'] )."' ";
            $sql   .= "AND `day` = '".mysql_real_escape_string( $data[$i]['day'] )."' ";
            $sql   .= "AND `year` = '".mysql_real_escape_string( $data[$i]['year'] )."' ";
            $sql   .= "ORDER BY `Id` DESC ";

            $res = mysql_query( $sql ) OR die( mysql_error() );

            if( mysql_num_rows( $res ) > 0 ) {
                $clockData = array();
                while( $row = mysql_fetch_assoc( $res ) ) {
                    $clockData[] = $row;
                }
                $data[$i]['multiple'] = true;

                $timeData = array();
                foreach( $clockData AS $key => $value ) {
                    $timeData[] = ( $value['outTimestamp'] - $value['inTimestamp'] );
                }

                $data[$i]['prevTime'] = array_sum( $timeData );
            }
            // END:   end check for multiple clock-in events

            $data[$i]['inTime'] = date( dateFormat, $data[$i]['inTimestamp'] );

            $wSql   = "SELECT * FROM `records` WHERE ";
            $wSql  .= "`week` = '".mysql_real_escape_string( $data[$i]['week'] )."' ";
            $wSql  .= "AND `year` = '".mysql_real_escape_string( $data[$i]['year'] )."' ";
            $wSql  .= "AND `id` <= '".mysql_real_escape_string( $data[$i]['Id'] )."' ";
            $wRes   = mysql_query( $wSql ) OR die( mysql_error() );

            $wData      = array();
            $weekHours  = array();

            while( $wRow = mysql_fetch_assoc( $wRes ) ) {
                if( strlen( $wRow['outTimestamp'] ) ) {
                    $wRow['totalHours'] = ( $wRow['outTimestamp'] - $wRow['inTimestamp'] ) - lunchBreakDuration;
                    $weekHours[]        = $wRow['totalHours'];
                } else {
                    $wRow['totalHours'] = ( time() - $wRow['inTimestamp'] ) - lunchBreakDuration;
                    $weekHours[]        = $wRow['totalHours'];
                }
            }

            $data[$i]['weekHours'] = sec2hms( array_sum( $weekHours ) );
            $data[$i]['wkBalance'] = ( (requiredHoursPerWeek * 3600) - ( array_sum( $weekHours ) ) );

            if(preg_match('/-/', $data[$i]['wkBalance'])) {
                preg_match('/(?P<digit>\d+)/', $data[$i]['wkBalance'], $matches);
                $data[$i]['wkBalance']  = '<font color="#4AA02C">+'.sec2hms($matches['digit']).'</font>';
                $data[$i]['inOverTime'] = true;
                $data[$i]['overTime']   = ((array_sum($weekHours)) - (requiredHoursPerWeek * 3600));
            } else {
                $data[$i]['wkBalance']  = '<font color="#FF0000">-'.sec2hms($data[$i]['wkBalance']).'</font>';
                $data[$i]['secsReq']    = ((requiredHoursPerWeek * 3600) - (array_sum($weekHours)));
            }

            if(!strlen($data[$i]['outTimestamp'])) {
                $data[$i]['outTime']    = '<b><font color="#FF0000">STILL CLOCKED IN</font></b>&nbsp;<button id="clockOutButton">Clock Out</button>';
                $data[$i]['weekHours']  = "<div id=\"wkHours\" style=\"height: 0px;\"></div>
                                            <script type=\"text/javascript\">
                                            $('#wkHours').countdown({
                                            since: new Date('".date('F d, Y H:i:s', (time() - array_sum($weekHours)))."'),
                                            format: 'HMS',                                            
                                            layout: '{hnn}{sep}{mnn}{sep}{snn}',
                                            });
                                            </script>";
                if(strlen(@$data[$i]['inOverTime'])) {
                    $data[$i]['wkBalance'] = "<div id=\"wkBal\" style=\"height: 0px;\"></div>
                                                <script type=\"text/javascript\">
                                                $('#wkBal').countdown({
                                                since: new Date('".date('F d, Y H:i:s', (time() - $data[$i]['overTime']))."'),
                                                format: 'HMS',
                                                layout: '<font color=\"#4AA02C\">+{hnn}{sep}{mnn}{sep}{snn}</font>',
                                                });
                                                </script>";
                } else {
                    $data[$i]['wkBalance'] = "<div id=\"wkBal\" style=\"height: 0px;\"></div>
                                                <script type=\"text/javascript\">
                                                $('#wkBal').countdown({
                                                until: new Date('".date('F d, Y H:i:s', (time() + $data[$i]['secsReq']))."'),
                                                format: 'HMS',
                                                layout: '<font color=\"#FF0000\">-{hnn}{sep}{mnn}{sep}{snn}</font>',
                                                });
                                                </script>";
                }
				
                // @todo	check this code for initial clock-in display
                $hoursWorked = time() - $data[$i]['inTimestamp'];
				if( $hoursWorked >= lunchBreakDuration ) {
					$lunchPassed = true;		
				} else {
					$lunchPassed = false;		
				}
                
				if( $lunchPassed ) {
				                $data[$i]['totalHours'] = "<div id=\"since\" style=\"height: 0px;\"></div>
                                            <script type=\"text/javascript\">
                                            $('#since').countdown({
                                            since: new Date('".date( 'F d, Y H:i:s', $data[$i]['inTimestamp'] + lunchBreakDuration )."'),
                                            format: 'HMS',                                            
                                            layout: '{hnn}{sep}{mnn}{sep}{snn}',
                                            });

                                            $('#clockOutButton').click(function() {
                                                $.post(BASEURL + '/index.php', { clockOut: 'true', Id: '".$data[$i]['Id']."'},
                                                    function(data) {
                                                        if(data == 'OK') {
                                                			reloadPage();
                                                            //$('#list').trigger('reloadGrid');
                                                        }
                                                    }
                                                );
                                             });
                                            </script>";	
				} else {
				                $data[$i]['totalHours'] = "<div id=\"since\" style=\"height: 0px;\"></div>
                                            <script type=\"text/javascript\">
                                            $('#since').countdown({
                                            until: new Date('".date( 'F d, Y H:i:s', $data[$i]['inTimestamp'] + ( requiredHoursPerDay * 3600 ) )."'),
                                            format: 'HMS',
                                            onExpiry: function() {
														reloadPage();
											},                                            
                                            layout: '<font color=\"red\">-{hnn}{sep}{mnn}{sep}{snn}</font>',
                                            });

                                            $('#clockOutButton').click(function() {
                                                $.post(BASEURL + '/index.php', { clockOut: 'true', Id: '".$data[$i]['Id']."'},
                                                    function(data) {
                                                        if(data == 'OK') {
                                                			reloadPage();
                                                            //$('#list').trigger('reloadGrid');
                                                        }
                                                    }
                                                );
                                             });
                                            </script>";	
				}

                // if this is not a required workday, then we display all time as extra
                if(!in_array(date('l', $data[$i]['inTimestamp']), $workDays)) {
                    $data[$i]['hoursOver'] = "<div id=\"over\" style=\"height: 0px; color: #4AA02C;\"></div>
                                                <script type=\"text/javascript\">
                                                $('#over').countdown({
                                                    since: new Date('".date('F d, Y H:i:s', ($data[$i]['inTimestamp']))."'),
                                                    format: 'HMS',
                                                    layout: '+{hnn}{sep}{mnn}{sep}{snn}',
                                                });

                                                $('#clockOutButton').click(function() {
                                                    $.post(BASEURL + '/index.php', { clockOut: 'true', Id: '".$data[$i]['Id']."'},
                                                        function(data) {
                                                            if(data == 'OK') {
                                                    			reloadPage();
                                                                //$('#list').trigger('reloadGrid');
                                                            }
														});
                                                    });
                                                </script>";
                } else {
                    if((time() - $data[$i]['inTimestamp']) >= (requiredHoursPerDay * 3600)) {
                        $data[$i]['hoursOver'] = "<div id=\"over\" style=\"height: 0px; color: #4AA02C;\"></div>
                                                    <script type=\"text/javascript\">
                                                    $('#over').countdown({
                                                        since: new Date('".date('F d, Y H:i:s', ($data[$i]['inTimestamp'] + 32400))."'),
                                                        format: 'HMS',
                                                        layout: '+{hnn}{sep}{mnn}{sep}{snn}',
                                                    });

                                                    $('#clockOutButton').click(function() {
                                                        $.post(BASEURL + '/index.php', { clockOut: 'true', Id: '".$data[$i]['Id']."'},
                                                            function(data) {
                                                                if(data == 'OK') {
                                                        			reloadPage();
                                                                    //$('#list').trigger('reloadGrid');
                                                                }
															});
                                                        });
                                                    </script>";
                    } else {
                        $data[$i]['hoursOver']  = "<div id=\"until\" style=\"height: 0px; color: #FF0000;\"></div>
                                                    <script type=\"text/javascript\">
                                                    $('#until').countdown({
                                                    until: new Date('".date('F d, Y H:i:s', ($data[$i]['inTimestamp'] + 32400))."'),
                                                    format: 'HMS',
                                                    layout: '-{hnn}{sep}{mnn}{sep}{snn}',
                                                    });
                                                    </script>";
                    }
                }
            } else {
                $data[$i]['outTime'] = date(dateFormat, $data[$i]['outTimestamp']);
                if(!isset($data[$i]['prevTime'])) {
                    $data[$i]['totalHours'] = sec2hms( ( $data[$i]['outTimestamp'] - $data[$i]['inTimestamp'] ) - lunchBreakDuration );
                } else {
                    $data[$i]['totalHours'] = sec2hms( ( ( $data[$i]['outTimestamp'] - $data[$i]['inTimestamp'] ) + $data[$i]['prevTime'] ) - lunchBreakDuration );
                }
                $data[$i]['hours'] = timestampToHours($data[$i]['outTimestamp'] - $data[$i]['inTimestamp']);
                if(!isset($data[$i]['prevTime'])) {
                    $data[$i]['minutes'] = timestampToMinutes($data[$i]['outTimestamp'] - $data[$i]['inTimestamp']);
                } else {
                    $data[$i]['minutes'] = timestampToMinutes(($data[$i]['outTimestamp'] - $data[$i]['inTimestamp']) + $data[$i]['prevTime']);
                }
                if(!isset($data[$i]['prevTime'])) {
                    $data[$i]['seconds'] = $data[$i]['outTimestamp'] - $data[$i]['inTimestamp'];
                } else {
                    $data[$i]['seconds'] = ($data[$i]['outTimestamp'] - $data[$i]['inTimestamp']) + $data[$i]['prevTime'];
                }
                $data[$i]['timeOver']   = $data[$i]['seconds'] - (requiredHoursPerDay * 3600);
                $data[$i]['minsOver']   = $data[$i]['minutes'] - (requiredHoursPerDay * 60);

                if(!in_array(date('l', $data[$i]['inTimestamp']), $workDays)) {
                    $data[$i]['hoursOver'] = '<font color="#4AA02C">+'.$data[$i]['totalHours'].'</font>';
                } else {
                    if(preg_match('/-/', $data[$i]['timeOver'])) {
                        preg_match('/(?P<digit>\d+)/', $data[$i]['timeOver'], $matches);
                        $data[$i]['negativeBalance']    = true;
                        $data[$i]['hoursOver']          = '<font color="#FF0000">-'.sec2hms($matches['digit']).'</font>';
                    } else {
                        $data[$i]['hoursOver'] = '<font color="#4AA02C">+'.sec2hms($data[$i]['timeOver']).'</font>';
                    }
                }
            }
        }

        // construct JSON
        $response->page     = $page;
        $response->total    = $total_pages;
        $response->records  = $count;

        for ($i = 0; $i < $count; $i++) {
            $response->rows[$i]['id']   = $data[$i]['Id'];
            $response->rows[$i]['cell'] = array($data[$i]['month'].' ('.date('F', $data[$i]['inTimestamp']).')',
                                                $data[$i]['day'].' ('.date('l', $data[$i]['inTimestamp']).')',
                                                $data[$i]['year'],
                                                $data[$i]['inTime'],
                                                $data[$i]['outTime'],
                                                $data[$i]['totalHours'],
                                                $data[$i]['hoursOver'],
                                                $data[$i]['weekHours'],
                                                $data[$i]['wkBalance']
                                                );
        }

        // return the formatted data
        echo(json_encode($response));
    }

    function outputSettingsJson()
    {
        if( empty( $_POST ) ) {
            return;
        }

        // get the requested page
        $page = $_POST['page'];
        // get how many rows we want to have into the grid
        // rowNum parameter in the grid
        $limit  = $_POST['rows'];
        // get index row - i.e. user click to sort
        // at first time sortname parameter - after that the index from colModel
        $sidx   = $_POST['sidx'];
        // sorting order - at first time sortorder
        $sord   = $_POST['sord'];
        $sidx   = (!$sidx) ? 1 : $sidx;

        // calculate the number of rows for the query. We need this to paging the result
        $count  = $this->rowCount();

        // calculation of total pages for the query
        if($count > 0) {
	        $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }

        // if for some reasons the requested page is greater than the total
        // set the requested page to total page
        if ($page > $total_pages) {
            $page = $total_pages;
        }

        // calculate the starting position of the rows
        $start = $limit * $page - $limit; // do not put $limit*($page - 1)
        // if for some reasons start position is negative set it to 0
        // typical case is that the user type 0 for the requested page
        if($start < 0) {
            $start = 0;
        }

        // the actual query for the grid data
        //$where=null, $order=null, $count=null, $offset=null;
        $sql    = "SELECT * FROM `config` ";
        $sql   .= "ORDER BY `".mysql_real_escape_string( $sidx )."` ".mysql_real_escape_string( $sord )." ";
        $sql   .= "LIMIT ".mysql_real_escape_string( $start ).", ".mysql_real_escape_string( $limit );

        $res    = mysql_query( $sql ) OR die( mysql_error().$sql );

        $count  = mysql_num_rows( $res );
        if( $count > 0 ) {
            $data = array();
            while( $row = mysql_fetch_assoc( $res ) ) {
                $data[] = $row;
            }
        }

        // construct JSON
        $response->page     = $page;
        $response->total    = $total_pages;
        $response->records  = $count;

        for ( $i = 0; $i < $count; $i++ ) {
            $response->rows[$i]['id']   = $data[$i]['Id'];
            $response->rows[$i]['cell'] = array($data[$i]['key'],
                                                $data[$i]['value'],
                                                $data[$i]['comment']
                                                );
        }

        // return the formatted data
        echo( json_encode( $response ) );
    }
}