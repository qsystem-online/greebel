<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menus {

	private $tblMenus = 'menus';
	public $arrMenu = [];
	public $CI;
	private $currentParent;
	private $currentMenu = null;

	public function __construct() {
		$this->CI = & get_instance();		
		$ssql = "select distinct a.*,ISNULL(b.fin_id) as noChild from " . $this->tblMenus . " a left join menus b on a.fin_id = b.fin_parent_id  where a.fbl_active = 1 order by a.fst_order ";
		$query = $this->CI->db->query($ssql,[]);
		$this->arrMenu = $query->result();

		$currLink = uri_string();
		foreach($this->arrMenu as $rw){
			if($rw->fst_link != null){
				if (preg_match('/^'.str_replace('/','\/',trim($rw->fst_link)) . '/i' ,$currLink)){
					$this->currentMenu = $rw;
					break;
				}else{
					//echo "'/'" .str_replace('/','\\/',$rw->fst_link) . "'/'" . " notmacth with " . $currLink ."<br>";
				}
			}
			$this->currentMenu = null;
		}
		//print_r($this->arrMenu);
		//echo "<br>";
		//echo "<h1>". $currLink ."</h1>";
		//echo "<h1>". print_r($this->currentMenu,true)."</h1>";

	}

	function arrMenuByParent($value){
		return ($value->fin_parent_id == $this->currentParent);
	}	

	public function build_menu($parent = 0){		
		$this->is_active(1);
		$this->currentParent = $parent;
		//$ssql = "select distinct a.*,ISNULL(b.fin_id) as noChild from " . $this->tblMenus . " a left join menus b on a.fin_id = b.fin_parent_id  where a.fin_parent_id = ? and a.fbl_active = 1 order by a.fst_order " ;
		//$query = $this->CI->db->query($ssql,array($parent));
		//$rs = $query->result();
		//echo "<h1> Start Req parent $parent " . date("H:i:s") ."</h1>";
		$rs = array_filter($this->arrMenu,array($this,"arrMenuByParent"));
		//echo "<h1> END Req parent $parent " . date("H:i:s") ."</h1>";

		$strMenu = "";
		foreach ($rs as $rw) {
			if ($rw->fst_type == "HEADER"){
				$strMenu .=  "<li class='header'>". $rw->fst_caption ."</li>";
			}else{
				if (!$this->CI->aauth->is_permit($rw->fst_menu_name)){
					continue;
				}

				$haveChild = !$rw->noChild; //$this->have_childs($rw->fin_id);				
				$foldElemet = $haveChild ? "<span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>" : "";
				$treeView = $haveChild ? "treeview" : "";

				$urlLink = ($rw->fst_link != null)  ? $rw->fst_link : "#";

				$isActive = ($this->is_active($rw->fst_link)) ? "active" : "";				
				//$isActiveParent = $this->is_active_parent($rw->fin_id) ? "menu-open" :"";
				$isActiveParent = $this->is_active_parent($rw->fst_order) ? "menu-open" :"";
				
				if ($isActiveParent == "menu-open"){
					$isActive = "active";
				}

				//$isActiveParentDisplay = $this->is_active_parent($rw->fin_id) ? "block" :"none";
				$isActiveParentDisplay = ($isActiveParent == "menu-open") ? "block" :"none";

				$strMenu .= "<li class='$isActive $treeView $isActiveParent'>
						<a href='" . site_url($urlLink) ."'>" . $rw->fst_icon . "<span>" .$rw->fst_caption ."</span>" . $foldElemet ."</a>";
				if ($haveChild){
					$strMenu .= "<ul class='treeview-menu' style='display:$isActiveParentDisplay'>";
					$strMenu .= $this->build_menu($rw->fin_id);
					$strMenu .= "</ul>";
				}
			}			
		}
		return $strMenu;
	}

	private function have_childs($menuId){
		$ssql = "select * from " . $this->tblMenus ." where fin_parent_id  =  $menuId limit 1";
		$qr = $this->CI->db->query($ssql,[]);
		$rw = $qr->row();
		if($rw){
			return true;
		}else{
			return false;
		}
	}


	private function is_active($link){
		if ($link == ""){
			return false;
		}
		if (preg_match('/'.str_replace('/', '\/', $link) .'/', uri_string())){
			return true;
		}else{
			return false;
		}
		
	}



	private function is_active_parent_old($id){
		$currLink = uri_string();
		$ssql = "select * from ". $this->tblMenus ." where ? like concat(fst_link,'%') order by (length(fst_link)) desc limit 1";
		$qr = $this->CI->db->query($ssql,array($currLink));
		$rw = $qr->row();
		if ($rw){
			if ($rw->fin_parent_id == $id){
				return true;
			}else{
				$doLoop = true;
				while($doLoop){
					$ssql = "select * from " . $this->tblMenus . " where fin_id  = ?";
					$qr = $this->CI->db->query($ssql,array($rw->fin_parent_id));
					$rw = $qr->row();
					if($rw){
						if ($rw->fin_parent_id == $id){
							return true;
						}
						if ($rw->fin_parent_id == 0){
							return false;
						}
					}else{
						return false;
					}

				}
			}
		}else{
			return false;
		}
	}

	private function is_active_parent($fst_order){
		//$currLink = uri_string();
		//Get Current menu
		if ($this->currentMenu == null){
			return false;
		}
		$result = preg_match('/^'. $fst_order .'/i',$this->currentMenu->fst_order);
		if ($result){
			return true;
		}
		return false;

	}
}