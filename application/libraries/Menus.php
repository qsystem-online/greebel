<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Menus {

	private $tblMenus = 'menus';
	public $CI;


	public function __construct() {
		$this->CI = & get_instance();
	}

	public function build_menu($parent = 0){
		$this->is_active(1);

		$ssql = "select * from " . $this->tblMenus . " where fin_parent_id = ? and fbl_active = 1 order by fin_order " ;
		$query = $this->CI->db->query($ssql,array($parent));

		$rs = $query->result();		
		$strMenu = "";

		foreach ($rs as $rw) {
			if ($rw->fst_type == "HEADER"){
				$strMenu .=  "<li class='header'>". $rw->fst_caption ."</li>";
			}else{
				if (!$this->CI->aauth->is_permit($rw->fst_menu_name)){
					continue;
				}

				$haveChild = $this->have_childs($rw->fin_id);				
				$foldElemet = $haveChild ? "<span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>" : "";
				$treeView = $haveChild ? "treeview" : "";

				$urlLink = ($rw->fst_link != null)  ? $rw->fst_link : "#";

				$isActive = ($this->is_active($rw->fst_link)) ? "active" : "";
				
				$isActiveParent = $this->is_active_parent($rw->fin_id) ? "menu-open" :"";
				if ($isActiveParent == "menu-open"){
					$isActive = "active";
				}

				$isActiveParentDisplay = $this->is_active_parent($rw->fin_id) ? "block" :"none";

				


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

	private function is_active_parent($id){
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
}