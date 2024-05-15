<style>div.container {
        width: 80%;
    }</style>
<div class="ps-page--single">
<div class="ps-breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>">Home</a></li>
            <li><a href="#">Members</a></li>
            <li><?php echo $title; ?></li>
        </ul>
    </div>
</div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
      <div class="ps-section__content">
        <?php 
          echo $this->session->flashdata('message'); 
          $this->session->unset_userdata('message');
        ?>
        <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 penjualan"><br>
        <div class='table-responsive'>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item"><a class="nav-link <?php echo ($_GET['page'] == '' ? 'active' : ''); ?>" href='<?= base_url(); ?>members/penjualan'>Menunggu Pembayaran <span class="badge badge-secondary"><?php echo total_penjualan_pending('0',reseller($this->session->id_konsumen)); ?></span></a></li>
                      <li class="nav-item"><a class="nav-link <?php echo ($_GET['page'] == 'siap' ? 'active' : ''); ?>" href='<?= base_url(); ?>members/penjualan?page=siap'>Lunas (Siap Dikirim) <span class="badge badge-secondary"><?php echo total_penjualan('1',reseller($this->session->id_konsumen)); ?></span></a></li>
                      <li class="nav-item"><a class="nav-link <?php echo ($_GET['page'] == 'dikirim' ? 'active' : ''); ?>" href='<?= base_url(); ?>members/penjualan?page=dikirim'>Sedang Dikirim <span class="badge badge-secondary"><?php echo total_penjualan('3',reseller($this->session->id_konsumen)); ?></span></a></li>
                      <li class="nav-item"><a class="nav-link <?php echo ($_GET['page'] == 'selesai' ? 'active' : ''); ?>" href='<?= base_url(); ?>members/penjualan?page=selesai'>Sampai Tujuan <span class="badge badge-secondary"><?php echo total_penjualan('4',reseller($this->session->id_konsumen)); ?></span></a></li>
                    </ul><br>


                      <?php if ($_GET['page']==''){ ?>
                      <table id="example1" class="table table-striped table-sm iconset">
                        <thead>
                          <tr>
                            <th style='width:40px'>No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Konsumen</th>
                            <th>Kurir</th>
                            <th>Status</th>
                            <th>Total + Ongkir</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                      <?php 
                        $no = 1;
                        foreach ($menunggu->result_array() as $row){
                        if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; $icon = 'star-empty'; $ubah = 1; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; $status = 'Pending'; $icon = 'star text-yellow'; $ubah = 0; }elseif($row['proses']=='3'){ 
                            $proses = '<i class="text-success">Dikirim</i>'; $status = 'Dikirim'; $icon = 'star text-yellow'; $ubah = 0; 
                        }else{ $proses = '<i class="text-info">Konfirmasi</i>'; $status = 'Proses'; $icon = 'star'; $ubah = 1; }
                        $total = $this->db->query("SELECT sum((a.harga_jual*a.jumlah)-a.diskon) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                        $kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                                    JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                                      where b.id_penjualan='$row[id_penjualan]'")->row_array();
                        $cekbayar = $this->db->query("SELECT * FROM rb_penjualan a JOIN rb_penjualan_otomatis b ON a.kode_transaksi=b.kode_transaksi where a.kode_kurir!='0' AND b.kode_transaksi='$row[kode_transaksi]' AND b.pembayaran='1'");
                        if($cekbayar->num_rows()>='1'){
                          $alert = "Apa anda yakin untuk ubah status jadi Proses?";
                        }else{
                          $alert = "PENTING - Pembayaran pesanan ini belum kami terima, yakin ingin melanjutkan untuk diproses?";
                        }
                        if($row['kode_kurir']=='0'){
                          $proses_dikirim = 3;
                        }else{
                          $proses_dikirim = 1;
                        }
                        $proses = strip_tags(status($row['proses']));
                        echo "<tr><td>$no</td>
                                  <td>$row[kode_transaksi]</td>
                                  <td><a href='".base_url().$this->uri->segment(1)."/detail_konsumen/$row[id_konsumen]'>$row[nama_lengkap]</a></td>
                                  <td>".kurir($row['kode_kurir'],$row['kurir'],$row['service'])."</td>
                                  <td>".status($row['proses']).'<br><small>'.status_pembayaran($row['proses'],$row['kode_transaksi'])."</small> </td>
                                  <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir']-$kupon['diskon'])."</td>
                                  <td><center>";
                                  if ($row['proses']=='0' OR $row['proses']=='2'){
                                    echo "<a class='btn btn-primary btn-xs' title='Proses Data' href='".base_url().$this->uri->segment(1)."/proses_penjualan/$row[id_penjualan]/$proses_dikirim' onclick=\"return confirm('$alert')\"><span class='fa fa-star'></span></a>";
                                  }else{
                                    echo "<a class='btn btn-default btn-xs' title='Proses Data' href='#' onclick=\"return alert('Maaf, Pesanan ini ($row[kode_transaksi]) dalam status $proses!')\"><span class='fa fa-star'></span></a>";
                                  }
                                    echo " <a class='btn btn-success btn-xs' title='Detail Data' href='".base_url().$this->uri->segment(1)."/detail_penjualan/$row[id_penjualan]' style='font-size:13px; padding:2px 10px'><span class='fa fa-search'></span> Detail</a>
                                  </center></td>
                              </tr>";
                          $no++;
                        }
                      ?>
                      </tbody>
                    </table>
                    <?php } ?>

                    <?php if ($_GET['page']=='siap'){ ?>
                    <table id="example11" class="table table-striped table-sm iconset">
                        <thead>
                          <tr>
                            <th style='width:40px'>No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Konsumen</th>
                            <th>Kurir</th>
                            <th>Status</th>
                            <th>Total + Ongkir</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                      <?php 
                        $no = 1;
                        foreach ($diterima->result_array() as $row){
                        if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; $icon = 'star-empty'; $ubah = 1; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; $status = 'Pending'; $icon = 'star text-yellow'; $ubah = 0; }elseif($row['proses']=='3'){ 
                            $proses = '<i class="text-success">Dikirim</i>'; $status = 'Dikirim'; $icon = 'star text-yellow'; $ubah = 0; 
                        }else{ $proses = '<i class="text-info">Konfirmasi</i>'; $status = 'Proses'; $icon = 'star'; $ubah = 1; }
                        $total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                        $kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                                    JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                                      where b.id_penjualan='$row[id_penjualan]'")->row_array();
                        echo "<tr><td>$no</td>
                                  <td>$row[kode_transaksi]</td>
                                  <td><a href='".base_url().$this->uri->segment(1)."/detail_konsumen/$row[id_konsumen]'>$row[nama_lengkap]</a></td>";
                                  if ($row['kode_kurir']=='1'){
                                    $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                    echo "<td>$row[service] - $ceks[merek]</td>";
                                  }elseif ($row['kode_kurir']=='0'){
                                    $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                    echo "<td>COD - $row[service]</td>";
                                  }else{
                                    echo "<td><span style='text-transform:uppercase'>$row[kurir]</span> - $row[service]</td>";
                                  }
                                  echo "<td>".status($row['proses'])."</td>
                                  <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir']-$kupon['diskon'])."</td>
                                  <td><center>
                                  <a class='btn btn-success btn-xs' title='Detail Data' href='".base_url().$this->uri->segment(1)."/detail_penjualan/$row[id_penjualan]' style='font-size:13px; padding:2px 10px'><span class='fa fa-search'></span> Detail</a>
                                  </center></td>
                              </tr>";
                          $no++;
                        }
                      ?>
                      </tbody>
                    </table>
                    <?php } ?>

                    <?php if ($_GET['page']=='dikirim'){ ?>
                    <table id="example12" class="table table-striped table-sm iconset">
                        <thead>
                          <tr>
                            <th style='width:40px'>No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Konsumen</th>
                            <th>Kurir</th>
                            <th>Status</th>
                            <th>Total + Ongkir</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                      <?php 
                        $no = 1;
                        foreach ($dikirim->result_array() as $row){
                        if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; $icon = 'star-empty'; $ubah = 1; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; $status = 'Pending'; $icon = 'star text-yellow'; $ubah = 0; }elseif($row['proses']=='3'){ 
                            $proses = '<i class="text-success">Dikirim</i>'; $status = 'Dikirim'; $icon = 'star text-yellow'; $ubah = 0; 
                        }else{ $proses = '<i class="text-info">Konfirmasi</i>'; $status = 'Proses'; $icon = 'star'; $ubah = 1; }
                        $total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                        $kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                                    JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                                      where b.id_penjualan='$row[id_penjualan]'")->row_array();
                        echo "<tr><td>$no</td>
                                  <td>$row[kode_transaksi]</td>
                                  <td><a href='".base_url().$this->uri->segment(1)."/detail_konsumen/$row[id_konsumen]'>$row[nama_lengkap]</a></td>";
                                  if ($row['kode_kurir']=='1'){
                                    $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                    echo "<td>$row[service] - $ceks[merek]</td>";
                                  }elseif ($row['kode_kurir']=='0'){
                                    $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                    echo "<td>COD - $row[service]</td>";
                                  }else{
                                    echo "<td><span style='text-transform:uppercase'>$row[kurir]</span> - $row[service]</td>";
                                  }
                                  echo "<td>".status($row['proses'])."</td>
                                  <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir']-$kupon['diskon'])."</td>
                                  <td><center>
                                  <a class='btn btn-success btn-xs' title='Detail Data' href='".base_url().$this->uri->segment(1)."/detail_penjualan/$row[id_penjualan]' style='font-size:13px; padding:2px 10px'><span class='fa fa-search'></span> Detail</a>
                                  </center></td>
                              </tr>";
                          $no++;
                        }
                      ?>
                      </tbody>
                    </table>
                    <?php } ?>

                    <?php if ($_GET['page']=='selesai'){ ?>
                    <table id="example13" class="table table-striped table-sm iconset">
                        <thead>
                          <tr>
                            <th style='width:40px'>No</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Konsumen</th>
                            <th>Kurir</th>
                            <th>Status</th>
                            <th>Total + Ongkir</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                      <?php 
                        $no = 1;
                        foreach ($selesai->result_array() as $row){
                        if ($row['proses']=='0'){ $proses = '<i class="text-danger">Pending</i>'; $status = 'Proses'; $icon = 'star-empty'; $ubah = 1; }elseif($row['proses']=='1'){ $proses = '<i class="text-success">Proses</i>'; $status = 'Pending'; $icon = 'star text-yellow'; $ubah = 0; }elseif($row['proses']=='3'){ 
                            $proses = '<i class="text-success">Dikirim</i>'; $status = 'Dikirim'; $icon = 'star text-yellow'; $ubah = 0; 
                        }else{ $proses = '<i class="text-info">Konfirmasi</i>'; $status = 'Proses'; $icon = 'star'; $ubah = 1; }
                        $total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                        $kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                                    JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                                      where b.id_penjualan='$row[id_penjualan]'")->row_array();
                        echo "<tr><td>$no</td>
                                  <td>$row[kode_transaksi]</td>
                                  <td><a href='".base_url().$this->uri->segment(1)."/detail_konsumen/$row[id_konsumen]'>$row[nama_lengkap]</a></td>";
                                  if ($row['kode_kurir']=='1'){
                                    $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                    echo "<td>$row[service] - $ceks[merek]</td>";
                                  }elseif ($row['kode_kurir']=='0'){
                                    $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                    echo "<td>COD - $row[service]</td>";
                                  }else{
                                    echo "<td><span style='text-transform:uppercase'>$row[kurir]</span> - $row[service]</td>";
                                  }
                                  echo "<td>".status($row['proses'])."</td>
                                  <td style='color:red;'>Rp ".rupiah($total['total']+$row['ongkir']-$kupon['diskon'])."</td>
                                  <td><center>
                                    <a class='btn btn-success btn-xs' title='Detail Data' href='".base_url().$this->uri->segment(1)."/detail_penjualan/$row[id_penjualan]' style='font-size:13px; padding:2px 10px'><span class='fa fa-search'></span> Detail</a>
                                  </center></td>
                              </tr>";
                          $no++;
                        }
                      ?>
                      </tbody>
                    </table>
                    <?php } ?>

                  </div>

              </div>
              </div>
              </div>
              </div>
              </div>
              </div>
              