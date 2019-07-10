<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class GLAccounts_model extends MY_Model
{
    public $tableName = "glaccounts";
    public $pkey = "GLAccountCode";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($GLAccountCode)
    {
        $ssql = "select a.*,b.CurrName, c.GLAccountName as GLParentName, d.GLAccountMainGroupName, d.GLAccountMainPrefix from " . $this->tableName . " a 
        left join mscurrencies b on a.CurrCode = b.CurrCode 
        left join " . $this->tableName . " c on a.ParentGLAccountCode = c.GLAccountCode
        left join glaccountmaingroups d on a.GLAccountMainGroupId = d.GLAccountMainGroupId
        where a.GLAccountCode = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$GLAccountCode]);
        $rwGLAccounts = $qr->row();

        $ssql = "select * from glaccounts where ParentGLAccountCode = ?";
        $qr = $this->db->query($ssql, [$GLAccountCode]);
        $rwParent = $qr->row();

        $data = [
            "glAccounts" => $rwGLAccounts,
            "parents" => $rwParent,
            "isUsed" => $this->isUsed($GLAccountCode)
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'GLAccountCode',
            'label' => 'GL Account Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.GLAccountCode.GLAccountCode.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];

        $rules[] = [
            'field' => 'GLAccountName',
            'label' => 'GL Account Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            ),
        ];

        $rules[] = [
            'field' => 'CurrCode',
            'label' => 'Current Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            ),
        ];

        return $rules;
    }

    public function isUsed($GLAccountCode){
        return true;
    }
}
