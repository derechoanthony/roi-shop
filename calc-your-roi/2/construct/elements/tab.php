<?php
	
	$tabKeys = array_keys(array_column($_SESSION['allTabs'], 'element_id'),$id);
?>		
			<div class="row">
                <div class="col-lg-12 form-horizontal">
                    <div class="tabs-container form-group">
                        <ul class="nav nav-tabs">
<?php
	
	$totalTabs = 1;
	foreach($tabKeys as $key){
		
		$tab = $_SESSION['allTabs'][$key];
		$tabName = 'TAB' . $tab['tab_id'];
		
		$inputKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$tabName);
		
		if($inputKey){
			$tabValue = $_SESSION['inputValues'][$inputKey[0]];
		}
		
		if(isset($tabValue)){
?>
	                        <li<?= $tabValue['value'] == $tab['value'] ? ' class="active"' : '' ?>><a class="tab-toggle" data-toggle="tab" href="#tab-<?= $tab['tab_id'] ?>"> <?= $tab['text'] ?></a></li>
<?php
		} else {
?>
							<li<?= $totalTabs == 1 ? ' class="active"' : '' ?>><a class="tab-toggle" data-toggle="tab" href="#tab-<?= $tab['tab_id'] ?>"> <?= $tab['text'] ?></a></li>		
<?php
		}	
		
		$totalTabs++;
	}
?>
                        </ul>
                        <div class="tab-content">
<?php
	
	$totalTabs = 1;
	foreach($tabKeys as $key){
		
		$tab = $_SESSION['allTabs'][$key];
		$tabName = 'TAB' . $tab['tab_id'];
		
		$inputKey = array_keys(array_column($_SESSION['inputValues'], 'entryid'),$tabName);
		
		if($inputKey){
			$tabValue = $_SESSION['inputValues'][$inputKey[0]];
		}
		
		if(isset($tabValue)){
?>
                            <div id="tab-<?= $tab['tab_id'] ?>" class="tab-pane<?= ( $tabValue['value'] == $tab['value'] ? ' active' : '' ) . ( $tab['resize'] ? ' tab-resize' : '' ) ?>">
<?php
		} else {
?>
							<div id="tab-<?= $tab['tab_id'] ?>" class="tab-pane<?= ( $totalTabs == 1 ? ' active' : '' ) . ( $tab['resize'] ? ' tab-resize' : '' ) ?>">
<?php
		}
?>
                                <div class="panel-body">
								<input type="hidden" 
									class="tab-active-input"
									<?= ( isset($tabValue) ? ' value="'. $tabValue['value'] .'"' : ( $totalTabs == 1 ? ' value="1"' : '' ) ) ?>
									name="TAB<?= $tab['tab_id'] ?>"
									data-cell="TAB<?= $tab['tab_id'] ?>"
									data-active-value="<?= $tab['value'] ?>">
<?php
		
		$tabKeys = array_keys(array_column($_SESSION['tabElements'], 'tab_id'),$tab['tab_id']);
		$parentKeys = array_keys(array_column($_SESSION['tabElements'], 'parent'),'0');

		$parentTabKeys = $tableHeaderKeys = array_intersect($tabKeys, $parentKeys);
		
		echo createTabElement($parentTabKeys);
?>
                                </div>
                            </div>
<?php
		$totalTabs++;
	}
?>
						</div>
					</div>
				</div>
			</div>