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
class Testimoni extends CI_Controller {
	public function index(){
		if (isset($_POST['submit'])){
			$this->model_reseller->insert_testimoni();
			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Berhasil dikirimkan dan akan muncul setelah disetujui oleh admin!!</center></div>');
			redirect('testimoni');
		}else{
			$jumlah= $this->model_reseller->hitung_testimoni()->num_rows();
			$config['base_url'] = base_url().'testimoni/index';
			$config['total_rows'] = $jumlah;
			$config['per_page'] = 6; 	
			if ($this->uri->segment('3')==''){
				$dari = 0;
			}else{
				$dari = $this->uri->segment('3');
			}
			if (is_numeric($dari)) {
				$data['record'] = $this->model_reseller->public_testimoni($config['per_page'], $dari);
			}else{
				redirect('testimoni');
			}
			$this->pagination->initialize($config);
			$data['title'] = 'Testimoni Konsumen';
			$data['description'] = description();
			$data['keywords'] = keywords();
			$this->template->load(template().'/template',template().'/reseller/view_testimoni',$data);
		}
	}
}
