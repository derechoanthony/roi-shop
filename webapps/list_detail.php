

<?php 

//require_once( "/core/init.php" ); 									// Sets up connection to database

$wbuserID = $_SESSION['userID'];


        $SQL = "SELECT *							
				FROM `wb_roi_list` t1
				WHERE t1.wbUserID=$wbuserID 
				ORDER BY t1.status DESC, t1.roiName;";
				
	$SQL = 	"	SELECT t1.wb_roi_ID, t1.CreatedBy, t1.wbUserID, t1.roiName, t1.dateCreated, t1.roiDescription, t1.status, t1.key,
				(SELECT COUNT(instanceID) FROM `wb_roi_instance` t2 WHERE (t2.dateCreated BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()) AND t2.wbroiID=t1.wb_roi_ID) LastWeek,
				(SELECT COUNT(instanceID) FROM `wb_roi_instance` t2 WHERE (t2.dateCreated BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND DATE_SUB(NOW(),INTERVAL 7 DAY)) AND t2.wbroiID=t1.wb_roi_ID) Last2Week,
				(SELECT COUNT(instanceID) FROM `wb_roi_instance` t2 WHERE (t2.dateCreated BETWEEN DATE_SUB(NOW(), INTERVAL 21 DAY) AND DATE_SUB(NOW(),INTERVAL 14 DAY)) AND t2.wbroiID=t1.wb_roi_ID) Last3Week,
				(SELECT COUNT(instanceID) FROM `wb_roi_instance` t2 WHERE (t2.dateCreated BETWEEN DATE_SUB(NOW(), INTERVAL 28 DAY) AND DATE_SUB(NOW(),INTERVAL 21 DAY)) AND t2.wbroiID=t1.wb_roi_ID) Last4Week,
				(SELECT COUNT(instanceID) FROM `wb_roi_instance` t2 WHERE (t2.dateCreated BETWEEN DATE_SUB(NOW(), INTERVAL 35 DAY) AND DATE_SUB(NOW(),INTERVAL 28 DAY)) AND t2.wbroiID=t1.wb_roi_ID) Last5Week,
				t3.company, t3.first AS CreatedByFirst, t3.last AS CreatedByLast 
				FROM `wb_roi_list` t1
                JOIN `wb_users` t3 ON t1.wbUserID = t3.wbuserID
				WHERE t1.wbUserID=$wbuserID 
				ORDER BY t1.status DESC, t1.roiName;";
		
		$list = $g->returnarray($SQL);
		$numrows = count($list);
		
		$rowdata = '';
        $pageinfo = '';
        
		if($numrows>0){
			foreach($list as $r){
				//Begin For loop for each list item
				$rowcount = $rowcount + 1/1;
				
				//Status Badge
		switch ($r['status']) {
		case 0:
			//This is a demo
			$badgecolor	='info';
			$badgetext	='Demo';
		break;
		case 1:
			//This is active
			$badgecolor	='primary';
			$badgetext	='Active';
			echo 'case 1';
		break;
		case 2:
			//This is lapsed
			$badgecolor	='danger';
			$badgetext	='Inactive';
		break;
		default:
			$badgecolor	='default';
			$badgetext	='Unknown';
		}
		
		//Get the creator of the Calculator
		if($r['company']=='theROIshop'){$createdby = '<img src="assets/img/logo-small.png" class="">';}
		else{
		if($r['CreatedBy']==$wbuserID){$createdby = 'Me';} else{$createdby = $r['CreatedByFirst'] . ' ' . $r['CreatedByLast'];}
		}
		
		$rowdata = $rowdata . '<tr>
								<td class="project-status">
                                    <span class="label label-' . $badgecolor . '">' . $badgetext . '</span>
                                </td>
                                <td class="project-title">
                                    ' . $r['roiName'] . '
                                    <br/>
                                    
                                </td>
                                <td class="project-title">
                                    ' . $createdby . '
                                    <br/>
                                    <small>Created On ' . $g->shortdate($r['dateCreated']) . '</small>
                                </td>
                                <td class="project-completion" data-sparkline="' . $r['Last5Week'] . ', ' . $r['Last4Week'] . ', ' . $r['Last3Week'] . ', ' . $r['Last2Week'] . ', ' . $r['LastWeek'] . '" />
                                        
                                
                                <td class="project-people">';

         
         $rowdata = $rowdata . $pageinfo . '</td>
         						<td class="project-actions">
         							<a href="details.php?wbappID=' . $r['wb_roi_ID'] . '&key=' . $r['key'] . '" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i> View </a>
                                    <a href="wb_editpage.php?u=' . $wbuserID . '&roi=' . $r['wb_roi_ID'] . '" class="btn btn-white btn-sm"><i class="fa fa-files-o"></i> Copy </a>
                                    <a href="wb_editpage.php?u=' . $wbuserID . '&roi=' . $r['wb_roi_ID'] . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete </a>
                                </td>
								
							</tr>';
				
		
			}	// End For Loop for each list item
		}
		
		//print_r ($list);
				
		 
		                    
        
			
		echo $rowdata;		
        ?>	
        
