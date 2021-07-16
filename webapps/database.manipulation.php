<?php

	include_once "../common/base.php";
	

	
	if( isset($_POST['action']) ) {	
	
	//Added for chart Options
	
	if( $_POST['action'] == 'storeChartOpts' ) {
			
				$sql = "UPDATE tbl_charts_list 
						SET chartName	=:chartName,
							compID		=:compID,
							chartType	= 0
						WHERE chartID=:chartID;";
				
				//echo $sql;
								
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':chartID', $_POST['chartID'], PDO::PARAM_INT);
				$stmt->bindParam(':chartName', $_POST['chartName'], PDO::PARAM_INT);
				$stmt->bindParam(':compID', $_POST['compID'], PDO::PARAM_STR);
				$stmt->execute();
				
				$sql = "UPDATE tbl_charts_options_general 
						SET titleText			=:titleText,
							titlefontID			=:titlefontID,
							titleColor			=:titleColor,
							titlefontsize		=:titlefontsize,
							titleBold			=:titleBold,
							titleItalic			=:titleItalic,
							titleUnderline		=:titleUnderline,
							subtitleText		=:subtitleText,
							subtitlefontID		=:subtitlefontID,
							subtitleColor		=:subtitleColor,
							subtitlefontsize	=:subtitlefontsize,
							subtitleBold		=:subtitleBold,
							subtitleItalic		=:subtitleItalic,
							subtitleUnderline	=:subtitleUnderline,
							backColor			=:backColor,
							borderColor			=:borderColor,
							borderThickness		=:borderThickness,
							borderRadius		=:borderRadius,
							xAxisTitleText		=:xAxisTitleText,
							xAxisTitleFontID	=:xAxisTitleFontID,
							xAxisTitlecolor		=:xAxisTitleColor,
							xAxisTitleFontSize	=:xAxisTitleFontSize,
							xAxisTitleBold		=:xAxisTitleBold,
							xAxisTitleItalic	=:xAxisTitleItalic,
							xAxisTitleUnderline	=:xAxisTitleUnderline,
							xAxisLabelsEnabled	=:xAxisLabelsEnabled,
							xAxisLabelsRotation	=:xAxisLabelsRotation,
							xAxisLabelsFontID	=:xAxisLabelsFontID,
							xAxisLabelsColor	=:xAxisLabelsColor,
							xAxisLabelsFontSize	=:xAxisLabelsFontSize,
							xAxisLabelsBold		=:xAxisLabelsBold,
							xAxisLabelsItalic	=:xAxisLabelsItalic,
							xAxisLabelsUnderline=:xAxisLabelsUnderline,
							LegendEnabled		=:LegendEnabled,
							legendItemFontID	=:legendItemFontID,
							legendItemFontSize	=:legendItemFontSize,
							legendItemColor		=:legendItemColor,
							legendItemBold		=:legendItemBold,
							legendItemItalic	=:legendItemItalic,
							legendItemUnderline	=:legendItemUnderline,
							legendBackColor		=:legendBackColor,
							legendBorderColor	=:legendBorderColor,
							legendBorderThickness		=:legendBorderThickness,
							legendBorderRadius	=:legendBorderRadius,
							legendAlign			=:legendAlign,
							legendVerticalAlign	=:legendVerticalAlign,
							legendx				=:legendx,
							legendy				=:legendy,
							legendLayout		=:legendLayout,
							legendShadow		=:legendShadow,
							legendShadowColor	=:legendShadowColor,
							legendTitle			=:legendTitle,
							legendTitleFontID	=:legendTitleFontID,
							legendTitleFontSize	=:legendTitleFontSize,
							legendTitleFontColor=:legendTitleFontColor,
							legendTitleBold		=:legendTitleBold,
							legendTitleItalic	=:legendTitleItalic,
							legendTitleUnderline=:legendTitleUnderline,
							legendPosition		=:legendPosition
							
						WHERE chartID=:chartID;";
				
				//print $sql;
								
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':chartID', $_POST['chartID'], PDO::PARAM_INT);
				$stmt->bindParam(':titleText', $_POST['titletext'], PDO::PARAM_INT);
				$stmt->bindParam(':titlefontID', $_POST['titlefontID'], PDO::PARAM_INT);
				$stmt->bindParam(':titleColor', $_POST['titleColor'], PDO::PARAM_STR);
				$stmt->bindParam(':titlefontsize', $_POST['titlefontsize'], PDO::PARAM_STR);
				$titleBold = (isset($_POST['titleBold']) ? 1 : 0);
				$stmt->bindParam(':titleBold', $titleBold, PDO::PARAM_STR);
				$titleItalic = (isset($_POST['titleItalic']) ? 1 : 0);
				$stmt->bindParam(':titleItalic', $titleItalic, PDO::PARAM_STR);
				$titleUnderline = (isset($_POST['titleUnderline']) ? 1 : 0);
				$stmt->bindParam(':titleUnderline', $titleUnderline, PDO::PARAM_STR);
				$stmt->bindParam(':subtitleText', $_POST['subtitletext'], PDO::PARAM_INT);
				$stmt->bindParam(':subtitlefontID', $_POST['subtitlefontID'], PDO::PARAM_INT);
				$stmt->bindParam(':subtitleColor', $_POST['subtitleColor'], PDO::PARAM_STR);
				$stmt->bindParam(':subtitlefontsize', $_POST['subtitlefontsize'], PDO::PARAM_STR);
				$subtitleBold = (isset($_POST['subtitleBold']) ? 1 : 0);
				$stmt->bindParam(':subtitleBold', $subtitleBold, PDO::PARAM_STR);
				$subtitleItalic = (isset($_POST['subtitleItalic']) ? 1 : 0);
				$stmt->bindParam(':subtitleItalic', $subtitleItalic, PDO::PARAM_STR);
				$subtitleUnderline = (isset($_POST['subtitleUnderline']) ? 1 : 0);
				$stmt->bindParam(':subtitleUnderline', $subtitleUnderline, PDO::PARAM_STR);
				$stmt->bindParam(':backColor', $_POST['backColor'], PDO::PARAM_STR);
				$stmt->bindParam(':borderColor', $_POST['borderColor'], PDO::PARAM_STR);
				$stmt->bindParam(':borderThickness', $_POST['borderThickness'], PDO::PARAM_STR);
				$stmt->bindParam(':borderRadius', $_POST['borderRadius'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisTitleText', $_POST['xAxisTitleText'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisTitleFontID', $_POST['xAxisTitleFontID'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisTitleColor', $_POST['xAxisTitleColor'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisTitleFontSize', $_POST['xAxisTitleFontSize'], PDO::PARAM_STR);
				$xAxisTitleBold = (isset($_POST['xAxisTitleBold']) ? 1 : 0);
				$stmt->bindParam(':xAxisTitleBold', $xAxisTitleBold, PDO::PARAM_STR);
				$xAxisTitleItalic = (isset($_POST['xAxisTitleItalic']) ? 1 : 0);
				$stmt->bindParam(':xAxisTitleItalic', $xAxisTitleItalic, PDO::PARAM_STR);
				$xAxisTitleUnderline = (isset($_POST['xAxisTitleUnderline']) ? 1 : 0);
				$stmt->bindParam(':xAxisTitleUnderline', $xAxisTitleUnderline, PDO::PARAM_STR);
				$xAxisLabelsEnabled = (isset($_POST['xAxisLabelsEnabled']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsEnabled', $xAxisLabelsEnabled, PDO::PARAM_STR);
				$stmt->bindParam(':xAxisLabelsRotation', $_POST['xAxisLabelsRotation'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisLabelsFontID', $_POST['xAxisLabelsFontID'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisLabelsColor', $_POST['xAxisLabelsColor'], PDO::PARAM_STR);
				$stmt->bindParam(':xAxisLabelsFontSize', $_POST['xAxisLabelsFontSize'], PDO::PARAM_STR);
				$xAxisLabelsBold = (isset($_POST['xAxisLabelsBold']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsBold', $xAxisLabelsBold, PDO::PARAM_STR);
				$xAxisLabelsItalic = (isset($_POST['xAxisLabelsItalic']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsItalic', $xAxisLabelsItalic, PDO::PARAM_STR);
				$xAxisLabelsUnderline = (isset($_POST['xAxisLabelsUnderline']) ? 1 : 0);
				$stmt->bindParam(':xAxisLabelsUnderline', $xAxisLabelsUnderline, PDO::PARAM_STR);
				$LegendEnabled = (isset($_POST['LegendEnabled']) ? 1 : 0);
				$stmt->bindParam(':LegendEnabled', $LegendEnabled, PDO::PARAM_STR);
				$stmt->bindParam(':legendItemFontID', $_POST['legendItemFontID'], PDO::PARAM_STR);
				$stmt->bindParam(':legendItemFontSize', $_POST['legendItemFontSize'], PDO::PARAM_STR);
				$stmt->bindParam(':legendItemColor', $_POST['legendItemColor'], PDO::PARAM_STR);
				$legendItemBold = (isset($_POST['legendItemBold']) ? 1 : 0);
				$stmt->bindParam(':legendItemBold', $legendItemBold, PDO::PARAM_STR);
				$legendItemItalic = (isset($_POST['legendItemItalic']) ? 1 : 0);
				$stmt->bindParam(':legendItemItalic', $legendItemItalic, PDO::PARAM_STR);
				$legendItemUnderline = (isset($_POST['legendItemUnderline']) ? 1 : 0);
				$stmt->bindParam(':legendItemUnderline', $legendItemUnderline, PDO::PARAM_STR);
				$stmt->bindParam(':legendBackColor', $_POST['legendBackColor'], PDO::PARAM_STR);
				$stmt->bindParam(':legendBorderColor', $_POST['legendBorderColor'], PDO::PARAM_STR);
				$stmt->bindParam(':legendBorderThickness', $_POST['legendBorderThickness'], PDO::PARAM_STR);
				$stmt->bindParam(':legendBorderRadius', $_POST['legendBorderRadius'], PDO::PARAM_STR);
				$stmt->bindParam(':legendAlign', $_POST['legendAlign'], PDO::PARAM_STR);
				$stmt->bindParam(':legendVerticalAlign', $_POST['legendVerticalAlign'], PDO::PARAM_STR);
				$stmt->bindParam(':legendx', $_POST['legendx'], PDO::PARAM_STR);
				$stmt->bindParam(':legendy', $_POST['legendy'], PDO::PARAM_STR);
				$stmt->bindParam(':legendLayout', $_POST['legendLayout'], PDO::PARAM_STR);
				$legendShadow = (isset($_POST['legendShadow']) ? 1 : 0);
				$stmt->bindParam(':legendShadow', $legendShadow, PDO::PARAM_STR);
				$stmt->bindParam(':legendShadowColor', $_POST['legendShadowColor'], PDO::PARAM_STR);
				
				$stmt->bindParam(':legendTitle', $_POST['legendTitle'], PDO::PARAM_INT);
				$stmt->bindParam(':legendTitleFontID', $_POST['legendTitleFontID'], PDO::PARAM_INT);
				$stmt->bindParam(':legendTitleFontSize', $_POST['legendTitleFontSize'], PDO::PARAM_INT);
				$stmt->bindParam(':legendTitleFontColor', $_POST['legendTitleFontColor'], PDO::PARAM_INT);
				
				$legendTitleBold = (isset($_POST['legendTitleBold']) ? 1 : 0);
				$stmt->bindParam(':legendTitleBold', $legendTitleBold, PDO::PARAM_STR);
				$legendTitleItalic = (isset($_POST['legendTitleItalic']) ? 1 : 0);
				$stmt->bindParam(':legendTitleItalic', $legendTitleItalic, PDO::PARAM_STR);
				$legendTitleUnderline = (isset($_POST['legendTitleUnderline']) ? 1 : 0);
				$stmt->bindParam(':legendTitleUnderline', $legendTitleUnderline, PDO::PARAM_STR);
				
				$stmt->bindParam(':legendPosition', $_POST['legendPosition'], PDO::PARAM_STR);
				
				
				$stmt->execute();
				
				echo $stmt;
		}
	
	
	//End Added for Chart Options
	
	
		if( $_POST['action'] == 'storevalues' ) {
			
			$sql = "SELECT * FROM roi_values
					WHERE roiid=:roi AND sessionid=:session AND entryid=:entry;";	

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchall();				
			
			if( $stmt->rowCount() > 0 ) {
				
				$sql = "UPDATE roi_values SET value=:value
						WHERE roiid=:roi AND sessionid=:session AND entryid=:entry;";
								
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
				$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_STR);
				$stmt->bindParam(':value', $_POST['val'], PDO::PARAM_STR);
				$stmt->execute();
			} else {
				
				$sql = "INSERT INTO roi_values (`roiid`,`value`,`sessionid`,`entryid`)
						VALUES (:roi,:value,:session,:entry);";
								
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
				$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_STR);
				$stmt->bindParam(':value', $_POST['val'], PDO::PARAM_STR);
				$stmt->execute();				
			}

		}
		
		if( $_POST['action'] == 'deletepdf' ) {		
			
			$sql = "DELETE FROM pdf_builder
					WHERE roi=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();

		}
	
		if( $_POST['action'] == 'storepdf' ) {		
			
			$sql = "UPDATE tbl_roi_list SET pdf = :pdf
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':pdf', $_POST['html'], PDO::PARAM_STR);
			$stmt->execute();

		}	
	
		if( $_POST['action'] == 'addRoi' ) {
			
			$sql = "SELECT * FROM users
					WHERE Username = :user";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam( ':user', $_SESSION['Username'], PDO::PARAM_STR );
			$stmt->execute();
			$data = $stmt->fetch();
			
			$sql = "UPDATE tbl_roi_list SET ListItemPosition = ListItemPosition + 1
					WHERE ListID = :user";

			$stmt = $db->prepare($sql);
			$stmt->bindParam( ':user', $data['UserID'], PDO::PARAM_INT );
			$stmt->execute();
			
			$sql = "SELECT currency FROM users
					WHERE Username=:user";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
			$stmt->execute();
			$cur = $stmt->fetch();
			$ver = sha1(uniqid(mt_rand(), true));
			$dt = date('Y-m-d H:i:s');		
			
			$sql = "INSERT INTO tbl_roi_list ( ListID, ListText, ListItemPosition, compStructure, ver_code, dt, currency )
					VALUES ( :user, :name, 1, :comp, :ver, :dt, :currency )";
		
			$stmt = $db->prepare($sql);
			$stmt->bindParam( ':user', $data['UserID'], PDO::PARAM_INT );
			$stmt->bindParam( ':name', $_POST['name'], PDO::PARAM_STR);
			$stmt->bindParam( ':comp', $_POST['comp'], PDO::PARAM_STR);
			$stmt->bindParam( ':ver', $ver, PDO::PARAM_STR);
			$stmt->bindParam( ':dt', $dt, PDO::PARAM_STR);
			$stmt->bindParam( ':currency', $cur['currency'], PDO::PARAM_STR);
			$stmt->execute();

			echo $db->lastInsertId();
			
		}

		if( $_POST['action'] == 'deleteroi' ) {		
			
			$sql = "DELETE FROM tbl_roi_list
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'resetpdf' ) {		
			
			$sql = "UPDATE tbl_roi_list SET pdf = ''
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'renameroi' ) {		
			
			$sql = "UPDATE tbl_roi_list SET ListText = :name
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			
			echo $_POST['name'];

		}
		
		if( $_POST['action'] == 'updatepersonal' ) {		
			
			$sql = "UPDATE users SET Username = :email, full_name = :fullname, phone = :phone
					WHERE Username = :user";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
			$stmt->bindParam(':fullname', $_POST['fullname'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
			$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
			$stmt->execute();
			
			$_SESSION['Username'] = $_POST['email'];
			
			echo 'updated';

		}
		
		if( $_POST['action'] == 'updatepassword' ) {		
			
			$sql = "UPDATE users SET Password = :pass
					WHERE Username = :user";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':pass', md5($_POST['newpassword']), PDO::PARAM_STR);
			$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
			$stmt->execute();
			
			echo 'updated';

		}

		if( $_POST['action'] == 'savesectionnotes' ) {		
			
			$date = date('Y-m-d H:i:s', time());
			$user = 1;
			$private = 1;
			
			$sql = "INSERT INTO section_notes ( roiid, sectionid, userid, dt, note, note_title, private )
					VALUES ( :roi, :section, :user, :dt, :note, :note_title, :private )";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':section', $_POST['section'], PDO::PARAM_INT);
			$stmt->bindParam(':user', $user, PDO::PARAM_INT);
			$stmt->bindParam(':dt', $date, PDO::PARAM_STR);
			$stmt->bindParam(':note', $_POST['note'], PDO::PARAM_STR);
			$stmt->bindParam(':note_title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':private', $private, PDO::PARAM_INT);
			$stmt->execute();
			
			echo $db->lastInsertId();

		}
		
		if( $_POST['action'] == 'deletenote' ) {		
			
			$sql = "DELETE FROM section_notes
					WHERE id = :id;";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_POST['noteid'], PDO::PARAM_INT);
			$stmt->execute();

		}

		if( $_POST['action'] == 'changepdf' ) {
			
			$sql = "SELECT * FROM pdf_builder
					WHERE element_id=:id AND roi=:roi;";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_POST['element'], PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$element_data = $stmt->fetchall();
			
			if($element_data) {
				
				$sql = "UPDATE pdf_builder SET html=:html, pos_x=:posx, pos_y=:posy, page=:page
						WHERE element_id=:id AND roi=:roi;";
						
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':html', $_POST['html'], PDO::PARAM_STR);
				$stmt->bindParam(':page', $_POST['page'], PDO::PARAM_INT);
				$stmt->bindParam(':posx', $_POST['posx'], PDO::PARAM_INT);
				$stmt->bindParam(':posy', $_POST['posy'], PDO::PARAM_INT);
				$stmt->bindParam(':id', $_POST['element'], PDO::PARAM_INT);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->execute();
				
			} else {
					
				$sql = "INSERT INTO pdf_builder (html, page, pos_x, pos_y, element_id, roi)
						VALUES (:html, :page, :posx, :posy, :element, :roi);";
			
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':html', $_POST['html'], PDO::PARAM_STR);
				$stmt->bindParam(':page', $_POST['page'], PDO::PARAM_INT);
				$stmt->bindParam(':posx', $_POST['posx'], PDO::PARAM_INT);
				$stmt->bindParam(':posy', $_POST['posy'], PDO::PARAM_INT);
				$stmt->bindParam(':element', $_POST['element'], PDO::PARAM_STR);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->execute();
			}
			
		}
		
		if( $_POST['action'] == 'storepdfspec' )	{
			
			$sql = "INSERT INTO pdf_specs (html, pageno, pos_x, pos_y, roi, width, content_type)
					VALUES (:html, :pageno, :posx, :posy, :roi, :width, :content);";
		
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':html', $_POST['html'], PDO::PARAM_STR);
			$stmt->bindParam(':posx', $_POST['posx'], PDO::PARAM_INT);
			$stmt->bindParam(':posy', $_POST['posy'], PDO::PARAM_INT);
			$stmt->bindParam(':width', $_POST['width'], PDO::PARAM_INT);
			$stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
			$stmt->bindParam(':pageno', $_POST['pageno'], PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();
			
		}

		if( $_POST['action'] == 'changesectioninput' ) {		
			
			$sql = "UPDATE entry_fields SET Title = :title, Type = :type, Format = :format, Tip = :tip
					WHERE ID = :id";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_INT);
			$stmt->bindParam(':format', $_POST['format'], PDO::PARAM_INT);
			$stmt->bindParam(':tip', $_POST['tip'], PDO::PARAM_STR);
			$stmt->execute();
		}

		if( $_POST['action'] == 'changediscoveryinput' ) {		
			
			$sql = "UPDATE discovery_questions SET Title = :title, Type = :type, Format = :format, Tip = :tip
					WHERE ID = :id";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_INT);
			$stmt->bindParam(':format', $_POST['format'], PDO::PARAM_INT);
			$stmt->bindParam(':tip', $_POST['tip'], PDO::PARAM_STR);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'addsection' ) {		
			
			$sql = "SELECT MAX(Position) AS LastPosition FROM compsections
					WHERE compID = :comp";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_POST['comp'], PDO::PARAM_INT);
			$stmt->execute();
			$lastpos = $stmt->fetch();
			$newpos = $lastpos['LastPosition'] + 1;
			
			$sql = "INSERT INTO compsections (compID, Title, Position)
					VALUES (:comp, :title, :pos)";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_POST['comp'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':pos', $newpos, PDO::PARAM_INT);
			$stmt->execute();
			
			echo $db->lastInsertId();
		}
		
		if( $_POST['action'] == 'updatesectionpos' ) {		
			
			$sql = "UPDATE compsections SET Position = :pos
					WHERE ID = :sectionid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':pos', $_POST['pos'], PDO::PARAM_INT);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'updateentrypos' ) {		
			
			$sql = "UPDATE entry_fields SET position = :pos
					WHERE ID = :entryid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':pos', $_POST['pos'], PDO::PARAM_INT);
			$stmt->bindParam(':entryid', $_POST['entryid'], PDO::PARAM_INT);
			$stmt->execute();
		}

		if( $_POST['action'] == 'deletesection' ) {		
			
			$sql = "UPDATE compsections SET inactive = 1
					WHERE ID = :sectionid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'updatesectionname' ) {		
			
			$sql = "UPDATE compsections SET Title = :title
					WHERE ID = :sectionid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':title', $_POST['sectionname'], PDO::PARAM_STR);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'updatevideosrc' ) {		
			
			$sql = "UPDATE compsections SET Video = :src
					WHERE ID = :sectionid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':src', $_POST['src'], PDO::PARAM_STR);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'addnewentry' ) {		

			$sql = "SELECT MAX(position) AS LastPosition FROM entry_fields
					WHERE roiID = :comp AND sectionName = :sectionid;";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_POST['comp'], PDO::PARAM_INT);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->execute();
			$lastpos = $stmt->fetch();
			$newpos = $lastpos['LastPosition'] + 1;
			
			$sql = "INSERT INTO entry_fields (roiID, Title, sectionName, position)
					VALUES (:comp, :title, :sectionid, :pos)";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_POST['comp'], PDO::PARAM_INT);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':pos', $newpos, PDO::PARAM_INT);
			$stmt->execute();
			
			echo $db->lastInsertId();
			
		}

		if( $_POST['action'] == 'addroi' ) {		

			$sql = "INSERT INTO comp_specs (compName, retPeriod, bCost, discovery, maxUsers, admin, parent)
					VALUES (:name, :ret, '0', '0', '1', '0', '0');";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
			$stmt->bindParam(':ret', $_POST['return'], PDO::PARAM_INT);
			$stmt->execute();
			
			$lastId = $db->lastInsertId();
			
			$sql = "INSERT INTO user_comps (UserID, CompID, permission)
					VALUES ('2', :comp, '1');";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $lastId, PDO::PARAM_INT);
			$stmt->execute();
			
			echo $lastId;
			
		}
		
		if( $_POST['action'] == 'changeentry' ) {		
			
			$sql = "UPDATE entry_fields SET Title = :title, Type = :type, Format = :format, Tip = :tip, Placeholder = :placeholder, append = :append, choices = :choices
					WHERE ID = :entryid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
			$stmt->bindParam(':entryid', $_POST['entryid'], PDO::PARAM_INT);
			$stmt->bindParam(':type', $_POST['type'], PDO::PARAM_INT);
			$stmt->bindParam(':format', $_POST['format'], PDO::PARAM_INT);
			$stmt->bindParam(':tip', $_POST['tip'], PDO::PARAM_STR);
			$stmt->bindParam(':placeholder', $_POST['placeholder'], PDO::PARAM_STR);
			$stmt->bindParam(':append', $_POST['append'], PDO::PARAM_STR);
			$stmt->bindParam(':choices', $_POST['choices'], PDO::PARAM_STR);
			$stmt->execute();
			
			echo 'changed';
		}
		
		if( $_POST['action'] == 'deleteentry' ) {		
			
			$sql = "DELETE FROM entry_fields
					WHERE ID=:entryid";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':entryid', $_POST['entryid'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'addnewtestimonial' ) {		

			$sql = "INSERT INTO testimonials (company_id, testimonial, author, timing)
					VALUES (:comp, :test, :author, '0');";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_POST['comp'], PDO::PARAM_INT);
			$stmt->bindParam(':test', $_POST['test'], PDO::PARAM_STR);
			$stmt->bindParam(':author', $_POST['author'], PDO::PARAM_STR);
			$stmt->execute();
			
			echo $db->lastInsertId();
			
		}
		
		if( $_POST['action'] == 'deletetestimonial' ) {		

			$sql = "DELETE FROM testimonials
					WHERE id=:testid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':testid', $_POST['testid'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'changetestimonial' ) {		
			
			$sql = "UPDATE testimonials SET testimonial = :test, author = :author
					WHERE id = :testid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':test', $_POST['test'], PDO::PARAM_STR);
			$stmt->bindParam(':author', $_POST['author'], PDO::PARAM_STR);
			$stmt->bindParam(':testid', $_POST['testid'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'changesectionwriteup' ) {		
			
			$sql = "UPDATE compsections SET Caption = :writeup
					WHERE ID = :sectionid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':writeup', $_POST['writeup'], PDO::PARAM_STR);
			$stmt->bindParam(':sectionid', $_POST['sectionid'], PDO::PARAM_INT);
			$stmt->execute();

		}

		if( $_POST['action'] == 'changepdfitem' ) {		
			
			$sql = "UPDATE pdf_specs SET html = :pdfhtml, pos_x = :posx, pos_y = :posy
					WHERE id = :pdfitemid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':pdfhtml', $_POST['pdfhtml'], PDO::PARAM_STR);
			$stmt->bindParam(':posx', $_POST['posx'], PDO::PARAM_INT);
			$stmt->bindParam(':posy', $_POST['posy'], PDO::PARAM_INT);
			$stmt->bindParam(':pdfitemid', $_POST['pdfitemid'], PDO::PARAM_INT);
			$stmt->execute();

		}	
		
		if( $_POST['action'] == 'changeformula' ) {		
			
			$sql = "UPDATE entry_fields SET formula = :formula
					WHERE ID = :entryid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':formula', $_POST['formula'], PDO::PARAM_STR);
			$stmt->bindParam(':entryid', $_POST['entryid'], PDO::PARAM_INT);
			$stmt->execute();

		}

		if( $_POST['action'] == 'savesfdclink' ) {		
			
			$sql = "UPDATE discovery_questions SET sfdc_element = :sfdc, sfdc_account = :account, sfdc_opportunity = :opportunity, sfdc_lead = :lead
					WHERE ID = :discoveryid";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':sfdc', $_POST['sfdclink'], PDO::PARAM_STR);
			$stmt->bindParam(':account', $_POST['account'], PDO::PARAM_INT);
			$stmt->bindParam(':opportunity', $_POST['opportunity'], PDO::PARAM_INT);
			$stmt->bindParam(':lead', $_POST['lead'], PDO::PARAM_INT);
			$stmt->bindParam(':discoveryid', $_POST['id'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'addnewfolder' ) {		
			
			$sql = "SELECT * FROM users
					WHERE Username = :user";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam( ':user', $_SESSION['Username'], PDO::PARAM_STR );
			$stmt->execute();
			$data = $stmt->fetch();		
			
			$sql = "INSERT INTO roi_folders (title, userid, global)
					VALUES (:title, :userid, '0')";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':title', $_POST['foldername'], PDO::PARAM_STR);
			$stmt->bindParam(':userid', $data['UserID'], PDO::PARAM_INT);
			$stmt->execute();
			
			echo $db->lastInsertId();

		}
		
		if( $_POST['action'] == 'changefolder' ) {			
			
			$sql = "UPDATE tbl_roi_list SET folder = :folder
					WHERE ROIID = :roi;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':folder', $_POST['folderid'], PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_POST['roiid'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'savesfdcverlink' ) {		
			
			$sql = "UPDATE comp_specs SET sfdc_ver_link = :sfdc, sfdc_account = :account, sfdc_opportunity = :opportunity, sfdc_lead = :lead
					WHERE compID = :comp";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':sfdc', $_POST['sfdclink'], PDO::PARAM_STR);
			$stmt->bindParam(':account', $_POST['account'], PDO::PARAM_INT);
			$stmt->bindParam(':opportunity', $_POST['opportunity'], PDO::PARAM_INT);
			$stmt->bindParam(':lead', $_POST['lead'], PDO::PARAM_INT);
			$stmt->bindParam(':comp', $_POST['comp'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_POST['action'] == 'visiblefolders' ) {			
			
			$sql = "SELECT * FROM users
					WHERE Username = :user";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam( ':user', $_SESSION['Username'], PDO::PARAM_STR );
			$stmt->execute();
			$data = $stmt->fetch();
			
			$sql = "DELETE FROM visible_folders WHERE `userid` = :userid;";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam( ':userid', $data['UserID'], PDO::PARAM_INT );
			$stmt->execute();		
			
			$folders = json_decode($_POST['folders'], true);

			foreach($folders as $folder) {
				
				$sql = "INSERT INTO visible_folders (`userid`, `folderid`)
						VALUES (:userid, :folderid);";
						
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':userid', $data['UserID'], PDO::PARAM_INT);
				$stmt->bindParam(':folderid', $folder, PDO::PARAM_INT);
				$stmt->execute();
				
			}

		}
		
		if( $_POST['action'] == 'logoutUser' ) {
			
			$sql = "UPDATE sessions SET `logoutdt` = NOW()
					WHERE id = :session;";
					
			$stmt = $db->prepare( $sql );
			$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'overrideoutput' ) {
			
			$sql = "SELECT * FROM user_output_value
					WHERE roiid=:roi AND sessionid=:session AND entryid=:entry;";
				
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();
					
			if( $stmt->rowCount() > 0 ) {
				
				$sql = "UPDATE user_output_value SET value=:value
						WHERE roiid=:roi AND sessionid=:session AND entryid=:entry;";
						
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
				$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
				$stmt->bindParam(':value', $_POST['value'], PDO::PARAM_STR);
				$stmt->execute();				
			} else {
				
				$sql = "INSERT INTO user_output_value (`roiid`,`value`,`sessionid`,`entryid`)
						VALUES (:roi,:value,:session,:entry);";
						
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':session', $_SESSION['id'], PDO::PARAM_INT);
				$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
				$stmt->bindParam(':value', $_POST['value'], PDO::PARAM_STR);
				$stmt->execute();				
			}
		}
		
		if( $_POST['action'] == 'exchangerates' ) {
			
			$getGMT = gmdate("Y-m-d H:i:s");
			
			$sql = "UPDATE exchange_rates SET `rate` = :rate, `dt` = :dt
					WHERE currency = :currency;";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
			$stmt->bindParam(':dt', $getGMT, PDO::PARAM_STR);
			$stmt->bindParam(':rate', $_POST['rate'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'storecurrency' ) {

			$sql = "SELECT * FROM roi_currency
					WHERE roiid = :roi;";
						
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->execute();	
			$stmt->fetchall();
			
			if( $stmt->rowCount() > 0 ) {
				
				$sql = "UPDATE roi_currency SET currency = :currency
						WHERE roiid = :roi;";

				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
				$stmt->execute();
			} else {
				
				$sql = "INSERT INTO roi_currency (`roiid`,`currency`)
						VALUES (:roi, :currency);";

				$stmt = $db->prepare($sql);
				$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
				$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
				$stmt->execute();			
			}
			
			$sql = "UPDATE tbl_roi_list SET currency = :language
					WHERE ROIID = :roi;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':language', $_POST['language'], PDO::PARAM_STR);
			$stmt->execute();
			
		}
		
		if( $_POST['action'] == 'changeManager' ) {
			
			$sql = "UPDATE users SET manager=:manager
					WHERE Username=:user";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':manager', $_POST['manager'], PDO::PARAM_STR);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'changeCurrency' ) {
			
			$sql = "UPDATE users SET currency=:currency
					WHERE Username=:user";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':currency', $_POST['currency'], PDO::PARAM_STR);
			$stmt->bindParam(':user', $_POST['user'], PDO::PARAM_STR);
			$stmt->execute();
		}
		
		if( $_POST['action'] == 'deleteoutputvalue' ) {
			
			$sql = "DELETE FROM user_output_value
					WHERE roiid = :roi AND entryid = :entry";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_POST['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':entry', $_POST['entry'], PDO::PARAM_INT);
			$stmt->execute();				
		}		
	}
	
	if( isset($_GET['action']) ) {
	
		if( $_GET['action'] == 'getvalues' ) {
			
			$sql = "SELECT MAX(sessionid) FROM roi_values
					WHERE roiid=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();
			
			$sql = "SELECT `value`, `entryid` FROM roi_values
					WHERE roiid=:roi AND sessionid=:session";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->bindParam(':session', $data['MAX(sessionid)'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();
			
			echo json_encode($data);
		}
		
		if( $_GET['action'] == 'delcont' ) {		
		
			$sql = "DELETE FROM createdwith
					WHERE id=:id";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();
			
		}
	
		if( $_GET['action'] == 'resetver' ) {		
			
			$ver = sha1(uniqid(mt_rand(), true));
			
			$sql = "UPDATE tbl_roi_list SET ver_code = :ver
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':ver', $ver, PDO::PARAM_STR);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			echo $ver;
			
		}
	
		if( $_GET['action'] == 'addcont' ) {
			
			$sql = "INSERT INTO createdwith ( roi, username)
					VALUES(:roi, :name)";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':name', $_GET['cont'], PDO::PARAM_STR);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			echo $db->lastInsertId();
			
		}
	
		if( $_GET['action'] == 'transferroi' ) {		
			
			$sql = "UPDATE tbl_roi_list SET ListID = :user, ListItemPosition = 1
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user', $_GET['user'], PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();

		}
		
		if( $_GET['action'] == 'getnotes' ) {
			
			$sql = "SELECT * FROM section_notes
					WHERE roiid = :roi AND sectionid = :section";		

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':section', $_GET['section'], PDO::PARAM_INT);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();
			
			echo json_encode($data);

		}
	
		if( $_GET['action'] == 'getverification' ) {		
		
			$sql = "SELECT ver_code FROM tbl_roi_list
					WHERE ROIID=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			echo $data['ver_code'];

		}
	
		if( $_GET['action'] == 'getcontributors' ) {		
			
			$sql = "SELECT * FROM createdwith
					WHERE roi=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();

			echo json_encode($data);
		}
		
		if( $_GET['action'] == 'getinputspecs' ) {		
			
			$sql = "SELECT * FROM entry_fields
					WHERE ID = :id";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			echo json_encode($data);
		}
	
		if( $_GET['action'] == 'getdiscoveryspecs' ) {		
			
			$sql = "SELECT * FROM discovery_questions
					WHERE ID = :id";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			echo json_encode($data);
		}
		
		if( $_GET['action'] == 'getcompsections' ) {		
			
			$sql = "SELECT * FROM compsections
					WHERE compID = :comp";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();

			echo json_encode($data);
		}
		
		if( $_GET['action'] == 'getpdflinespecs' ) {		
		
			$sql = "SELECT * FROM pdf_specs
					WHERE id=:item";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':item', $_GET['pdfitem'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			echo json_encode($data);

		}
		
		if( $_GET['action'] == 'getdiscoveryquestions' ) {		
			
			$sql = "SELECT * FROM discovery_questions
					WHERE discovery_id = :disc;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':disc', $_GET['discoveryid'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();

			echo json_encode($data);
		}
		
		if( $_GET['action'] == 'companyusers' ) {			
			
			$sql = "SELECT * FROM users
					WHERE compName = (
						SELECT compName FROM users
						WHERE Username = :user
					);";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchall();

			echo json_encode($data);

		}
	
		if( $_GET['action'] == 'getsfdcverlink' ) {			
			
			$sql = "SELECT sfdc_ver_link FROM comp_specs
					WHERE compID = :comp;";
					
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':comp', $_GET['comp'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();

			echo $data['sfdc_ver_link'];

		}
	
		if( $_GET['action'] == 'convertvalues' ) {
		
			ini_set('max_execution_time', 7200);
			
			$sql = "SELECT * FROM tbl_roi_list ORDER BY ROIID";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();

			foreach($data as $value) {
				
				$roi_value = unserialize( base64_decode( gzuncompress( $value['roi_values'] )));
				
				foreach($roi_value as $newvalue) {
					
					$sql = "INSERT INTO roi_values (`roiid`, `value`, `sessionid`, `entryid`)
							VALUES (:roiid, :val, '1', :entryid)";
				
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':roiid', $value['ROIID'], PDO::PARAM_INT);
					$stmt->bindParam(':val', $newvalue[1], PDO::PARAM_STR);
					$stmt->bindParam(':entryid', $newvalue[0], PDO::PARAM_STR);
					$stmt->execute();			
				}
			}
			
		}

		if( $_GET['action'] == 'getoverriddenoutput' ) {
			
			$sql = "SELECT * FROM user_output_value
					WHERE roiid=:roi";

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':roi', $_GET['roi'], PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchall();

			echo json_encode($data);		
		}

		if( $_GET['action'] == 'lastexchangeupdate' ) {

			$sql = "SELECT MAX(dt) FROM exchange_rates;";
						
			$stmt = $db->prepare($sql);
			$stmt->execute();	
			$data = $stmt->fetch();
			
			echo $data['MAX(dt)'];
			
		}
		
		

	}
?>