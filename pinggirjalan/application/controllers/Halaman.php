<?php
/*
-- ---------------------------------------------------------------
-- MARKETPLACE MULTI SELLER PRO
-- CREATED BY : HAMBA ALLAH mycoding.net
-- COPYRIGHT  : Copyright (c) 2018 - 2021, MCP. (https://mycoding.id)
-- LICENSE    : FREE
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2021-02-09
-- ---------------------------------------------------------------
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Halaman extends CI_Controller {
	public function detail(){
		$query = $this->db->query("SELECT * FROM halamanstatis a LEFT JOIN users b ON a.username=b.username where a.judul_seo='".cetak($this->uri->segment(3))."'");
		if ($query->num_rows()<=0){
			redirect('main');
		}else{
			$row = $query->row_array();
			$data['title'] = cetak($row['judul']);
			$data['description'] = cetak($row['isi_halaman']);
			$data['keywords'] = cetak(str_replace(' ',', ',$row['judul']));
			$data['rows'] = $row;

			$dataa = array('dibaca'=>$row['dibaca']+1);
			$where = array('id_halaman' => $row['id_halaman']);
			$this->model_utama->update('halamanstatis', $dataa, $where);
			$this->template->load(template().'/template',template().'/detailhalaman',$data);
		}
	}
}
