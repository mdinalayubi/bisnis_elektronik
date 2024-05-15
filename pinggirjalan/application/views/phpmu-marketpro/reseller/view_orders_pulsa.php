<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li>Transaksi Pulsa</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php include "menu-members.php"; 
                echo $this->session->flashdata('message'); 
                $this->session->unset_userdata('message');
            ?>
            <div class="row">
                
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                      include "sidebar-members.php";
                    ?><div style='clear:both'><br></div>
                </div>
                
                <div class='col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 biodata notif'>
                        <?php 
                            $rows = $this->db->query("SELECT maps FROM identitas where id_identitas='1'")->row_array();
                            if ($rows['maps']!='|'){
                                $maps = explode('|',$rows['maps']);
                                $url1 = 'https://tripay.co.id/api/v2/pembelian/operator/';
                                $header = array(
                                'Accept: application/json',
                                'Authorization: Bearer '.$maps[0], // Ganti [apikey] dengan API KEY Anda
                                );
                                $ch1 = curl_init();
                                curl_setopt($ch1, CURLOPT_URL, $url1);
                                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($ch1, CURLOPT_HTTPHEADER, $header);
                                curl_setopt($ch1, CURLOPT_POST, 1);
                                $operator = curl_exec($ch1);
                                if(curl_errno($ch1)){
                                    return 'Request Error:' . curl_error($ch1);
                                }
                                $operator = json_decode($operator);
                            }
                             if ($rows['maps']!='|' AND trim($rows['maps'])!='||'){
                                echo "<div class='ps-block--site-features' style='margin-bottom:20px; background-color:#fff; border: none; padding: 10px 0px;'>";
                                    echo "<form class='col-md-12' style='padding-right:0px; padding-left:0px' method='POST' action='".base_url()."main/proses?trx_pulsa=1'>
                                    <div class='form-row'>
                                    <div style='margin-bottom:0px' class='col-md-4 form-group'>
                                      <input type='number' name='tujuan' class='form-control' placeholder='Masukkan Nomor Handphone,..' required>
                                    </div>
                                    
                                    <div style='margin-bottom:0px' class='col-md-3 form-group'>
                                      <select name='operator' class='form-control' id='operator' required>
                                      <option value=''>- Pilih Operator -</option>";
                                      foreach($operator->data as $item){
                                        if ($item->pembeliankategori_id=='1'){
                                          if ($item->status=='0'){ $status = 'disabled'; }else{ $status = ''; }
                                          echo "<option value='".$item->id."' $status>".$item->product_name."</option>";
                                        }
                                      }
                                      echo "</select>
                                    </div>
                                    
                                    <div style='margin-bottom:0px' class='col-md-3 form-group'>
                                      <select name='produk' class='form-control' id='produk' required>
                                        <option value=''>- Pilih Produk -</option>
                                      </select>
                                    </div>
                                    
                                    <div style='margin-bottom:0px' class='col-md-2 form-group'>
                                      <button type='submit' name='submit' style='padding:9px 12px' class='ps-btn ps-btn--fullwidth spinnerButton'>Beli Pulsa</button>
                                    </div>
                                    </form>
                                    </div>
                                    <div style='clear:both'></div>";
                            echo "</div>";
                            }

                          if ($pulsa->num_rows()<=0){
                            echo "<div class='alert alert-info'><strong>INFORMASI</strong> - Halo kak, Saat ini Belum ada transaksi pembelian pulsa. <br> Yuk mari, jangan lupa isi pulsa dulu <a href='".base_url()."' style='color:#000'><b>disini</b></a>.</div>";
                          }
                          $no = 1;
                          foreach ($pulsa->result_array() as $row){
                          $ex = explode('|',$row['keterangan']);
                          echo "<div class='form-group row' style='margin-bottom:5px; background: #efefef;'>
                          <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Waktu Transaksi</label>
                            <div class='col-sm-10'>
                              ".jam_tgl_indo($row['waktu_pembelian'])."
                          </div>
                          </div>

                          <div class='form-group row' style='margin-bottom:5px'>
                          <label class='col-sm-2 col-form-label' style='margin-bottom:1px'>Keterangan</label>
                            <div class='col-sm-10'>
                              Total <span style='color:Red'>Rp ".rupiah($row['total'])."</span> :  Pulsa ".$ex[1].", Tujuan <b>".$ex[2]."</b><br>
                            </div>
                          </div><br>";
                            $no++;
                          }
                        ?>

                    </div>
                    <div class="ps-pagination">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
              
