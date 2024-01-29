<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }

	function kt_build_h(){
		global $SITEURL;
		if(!isset($_GET['old']) && !isset($_GET['new']))
			$olnew = 'old';
		else{
			$olnew = (isset($_GET['old'])) ? 'new' : 'old';
			$olnew = (isset($_GET['new'])) ? 'old' : 'new';
		}
		$kturl = $SITEURL . 'admin/load.php?id=kt-blocklogin&'. $olnew . '=1';
		$kth5 = '<h5>Click <a href="'.$kturl .'">Here</a> to view ' . $olnew .' log file</h5>';
		return $kth5;
	}

	function kt_quick_check($counter, $timestamp){
		$currenttime = time();
		return ( ($counter %3 ==0)  && ($currenttime - $timestamp < 3600 ) ) ? 'Blocked' : 'Not blocked';
	}

	function kt_build_table($fileToOpen = KTFAILEDPATH) {
		$retstring = '';
		$handle = new XMLReader();
		$handle->open($fileToOpen,LIBXML_NOBLANKS) ;
		while($handle->read()){
			if($handle->hasAttributes)
				$retstring .= '<tr><td scope = "row" >' . str_replace('ip','',$handle->name) . '</td><td>' . $handle->getAttribute('counter') . '</td><td>' . kt_quick_check($handle->getAttribute('counter'),$handle->getAttribute('start')) . '</td><td>'. date('e'). '</td><td>' . date( 'd.m.Y H:i', $handle->getAttribute('start')) . '</td>' ;
		}
		if($retstring == '')
			$retstring = '<tr><td colspan="5"> Nothing found</td></tr>' ;
		echo $retstring ;
	}

	$kturl = (file_exists(KTFAILEDPATHBU)) ? kt_build_h() : '';

?>

	<style>
		#kt-display-table{
		max-height: 450px;
		overflow-x: hidden;
		}
		#kt-display-table  td:nth-child(2){
			text-align: center;
		}
		#kt-display-table  tr:nth-child(even) {
			background-color: #F0F0F0;
		}
	</style>

	<div id= 'kt-display-table'> 
		<h3>IP Blocker</h3><hr>
		<?php echo $kturl?>
		<table>
			<thead>
				<tr>
					<th scope="col">IP address</th>
					<th scope="col">Total attempts</th>
					<th scope="col">State</th>
					<th scope="col">Timezone</th>
					<th scope="col">last try</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(isset($_GET['old'])) 
					kt_build_table(KTFAILEDPATHBU);
				else 
					kt_build_table(KTFAILEDPATH);
				?>
			</tbody>
			
		</table>
	</div>