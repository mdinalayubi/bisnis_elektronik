<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Rute dan Ongkir Driver</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/tambah_ongkir_driver',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Kendaraan</th>    <td><select class='form-control' name='a' required>
                      <option value=''>- Pilih -</option>";
                    $jenis_kendaraan = $this->db->query("SELECT * FROM rb_jenis_kendaraan");
                    foreach ($jenis_kendaraan->result_array() as $row) {
                      echo "<option value='$row[id_jenis_kendaraan]'>$row[jenis_kendaraan]</option>";
                    }
                    echo "</select></td></tr>
                    <tr><th scope='row'>Posisi</th>      <td>
                    
                      <select class='form-control form-mini' style='width:35%; display:inline-block' name='provinsi_id' id='list_provinsi' required>";
                      echo "<option value=0>- Pilih Provinsi -</option>";
                      $provinsi = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
                      foreach ($provinsi->result_array() as $row) {
                        echo "<option value='$row[province_id]'>$row[province_name]</option>";
                      }
                      echo "</select>

                      <select class='form-control form-mini' style='width:33%; display:inline-block' name='kota_id' id='list_kotakab' required>
                      <option value=0>- Pilih Kota / Kabupaten -</option>
                      </select>

                      <select class='form-control form-mini' style='width:30%; display:inline-block' name='kecamatan_id' id='list_kecamatan' required>
                        <option value=0>- Pilih Kecamatan -</option>
                      </select>
                    
                    </td></tr>
                    <tr><th scope='row'>Tujuan</th>      <td>
                      <select class='form-control form-mini' style='width:35%; display:inline-block' name='provinsi_idt' id='list_provinsit' required>";
                      echo "<option value=0>- Pilih Provinsi -</option>";
                      $provinsi1 = $this->db->query("SELECT * FROM tb_ro_provinces ORDER BY province_name ASC");
                      foreach ($provinsi1->result_array() as $row) {
                        echo "<option value='$row[province_id]'>$row[province_name]</option>";
                      }
                      echo "</select>

                      <select class='form-control form-mini' style='width:33%; display:inline-block' name='kota_idt' id='list_kotakabt' required>
                      <option value=0>- Pilih Kota / Kabupaten -</option>
                      </select>

                      <select class='form-control form-mini' style='width:30%; display:inline-block' name='kecamatan_idt' id='list_kecamatant' required>
                        <option value=0>- Pilih Kecamatan -</option>
                      </select>
                    </td></tr>
                    <tr><th scope='row'>Ongkir (Rp)</th>    <td><input type='number' class='form-control' name='d'></td></tr>
                    <tr><th scope='row'>Keterangan</th>    <td><textarea class='form-control' style='height:80px' name='e'></textarea></td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Tambahkan</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
?>
<script>
$(document).ready(function(){
//* select Provinsi */
var base_url    = "<?php echo base_url();?>";
$("#list_provinsi").change(function(){
    var id_province = this.value;
    kota(id_province);
    $("#div_kota").show();
});

/* select Kota */
kota = function(id_province){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kota',
    data: {id_province:id_province},
    dataType  : 'html',
    success: function (data) {
        $("#list_kotakab").html(data);
    },
    beforeSend: function () {
        
    },
    complete: function () {
      
    }
});
}

$("#list_kotakab").change(function(){
    var id_kota = this.value;
    kecamatan(id_kota);
    $("#div_kecamatan").show();
});

kecamatan = function(id_kota){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kecamatan',
    data: {id_kota:id_kota},
    dataType  : 'html',
    success: function (data) {
        $("#list_kecamatan").html(data);
    }
});
}
});


$(document).ready(function(){
//* select Provinsi */
var base_url    = "<?php echo base_url();?>";
$("#list_provinsit").change(function(){
    var id_province = this.value;
    kotat(id_province);
    $("#div_kotat").show();
});

/* select Kota */
kotat = function(id_province){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kota',
    data: {id_province:id_province},
    dataType  : 'html',
    success: function (data) {
        $("#list_kotakabt").html(data);
    },
    beforeSend: function () {
        
    },
    complete: function () {
      
    }
});
}

$("#list_kotakabt").change(function(){
    var id_kota = this.value;
    kecamatant(id_kota);
    $("#div_kecamatant").show();
});

kecamatant = function(id_kota){
    $.ajax({
    type: 'post',
    url: base_url + 'produk/rajaongkir_get_kecamatan',
    data: {id_kota:id_kota},
    dataType  : 'html',
    success: function (data) {
        $("#list_kecamatant").html(data);
    }
});
}
});
</script>
