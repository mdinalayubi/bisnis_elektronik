<script language="JavaScript" type="text/JavaScript">
  function showSub(){
    <?php
    $query = $this->db->query("SELECT * FROM rb_kategori_produk");
    foreach ($query->result_array() as $data) {
       $id_kategori_produk = $data['id_kategori_produk'];
       echo "if (document.demo.a.value == \"".$id_kategori_produk."\")";
       echo "{";
       $query_sub_kategori = $this->db->query("SELECT * FROM rb_kategori_produk_sub where id_kategori_produk='$id_kategori_produk'");
       $content = "document.getElementById('sub_kategori_produk').innerHTML = \"  <option value=''>- Pilih Sub Kategori Produk -</option>";
       foreach ($query_sub_kategori->result_array() as $data2) {
           $content .= "<option value='".$data2['id_kategori_produk_sub']."'>".$data2['nama_kategori_sub']."</option>";
       }
       $content .= "\"";
       echo $content;
       echo "}\n";
    }
    ?>
    }
</script>

<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Produk Terpilih</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form','name'=>'demo');
              echo form_open_multipart('administrator/edit_produk',$attributes);
              $disk = $this->model_app->edit('rb_produk_diskon',array('id_produk'=>$rows['id_produk']))->row_array();
              if ($rows['id_reseller']=='0'){
                  $jual = $this->model_reseller->jual($rows['id_produk'])->row_array();
                  $beli = $this->model_reseller->beli($rows['id_produk'])->row_array();
              }else{
                  $jual = $this->model_reseller->jual_reseller($rows['id_reseller'],$rows['id_produk'])->row_array();
                  $beli = $this->model_reseller->beli_reseller($rows['id_reseller'],$rows['id_produk'])->row_array();
              }

          echo "<div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                      <input type='hidden' name='idr' value='$rows[id_reseller]'>
                      <input type='hidden' name='id' value='$rows[id_produk]'>
                      <tr><th width='130px'scope='row'>Pemilik</th>                   <td><select style='color:red' name='id_reseller' class='form-control combobox' required>
                                          <option value='0' selected>Perusahaan</option>";
                                          $reseller = $this->db->query("SELECT * FROM rb_reseller");
                                          foreach ($reseller->result_array() as $row){
                                            if ($rows['id_reseller']==$row['id_reseller']){
                                              echo "<option value='$row[id_reseller]'>$row[nama_reseller]</option>";
                                            }else{
                                              echo "<option value='$row[id_reseller]' selected>$row[nama_reseller]</option>";
                                            }
                                          }
                      echo "</td></tr>
                      <tr><th scope='row'>Kategori</th>                   <td><select name='a' class='form-control' onchange=\"showSub()\" required>
                                                                              <option value='' selected>- Pilih Kategori Produk -</option>";
                                                                              foreach ($record as $row){
                                                                                if ($rows['id_kategori_produk']==$row['id_kategori_produk']){
                                                                                  echo "<option value='$row[id_kategori_produk]' selected>$row[nama_kategori]</option>";
                                                                                }else{
                                                                                  echo "<option value='$row[id_kategori_produk]'>$row[nama_kategori]</option>";
                                                                                }
                                                                              }
                      echo "</select></td></tr>
                      <tr><th scope='row'>Sub Kategori</th>                   <td><select name='aa' class='form-control' id='sub_kategori_produk'>
                                                                                  <option value='' selected>- Pilih Sub Kategori Produk -</option>";
                                                                                  $sub_kategori_produk = $this->db->query("SELECT * FROM rb_kategori_produk_sub");
                                                                                  foreach ($sub_kategori_produk->result_array() as $row){
                                                                                    if ($rows['id_kategori_produk_sub']==$row['id_kategori_produk_sub']){
                                                                                      echo "<option value='$row[id_kategori_produk_sub]' selected>$row[nama_kategori_sub]</option>";
                                                                                    }else{
                                                                                      echo "<option value='$row[id_kategori_produk_sub]'>$row[nama_kategori_sub]</option>";
                                                                                    }
                                                                                  }
                      echo "</select></td></tr>
                      <tr><th width='130px' scope='row'>Nama Produk</th>  <td><input type='text' class='form-control' name='b' value='$rows[nama_produk]' required></td></tr>
                      <tr><th scope='row'>Satuan</th>                     <td><input type='text' class='form-control' name='c' value='$rows[satuan]'></td></tr>
                      <tr><th scope='row'>Berat / Gram</th>                      <td><input type='number' class='form-control' name='berat' value='$rows[berat]'></td></tr>
                      <tr><th scope='row'>Harga Modal</th>                 <td><input type='text' class='form-control formatNumber' name='d' value='".rupiah($rows['harga_beli'])."'></td></tr>
                      <tr><th scope='row'>Harga Reseller</th>             <td><input type='text' class='form-control formatNumber' name='e' value='".rupiah($rows['harga_reseller'])."'></td></tr>
                      <tr><th scope='row'>Harga Konsumen</th>             <td><input type='text' class='form-control formatNumber' name='f' value='".rupiah($rows['harga_konsumen'])."'></td></tr>
                    </tbody>
                  </table>
                </div>
                <div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                      <tr><th scope='row'>Diskon</th>                 <td><input type='text' class='form-control formatNumber' name='diskon' value='".rupiah($disk['diskon'])."'></td></tr>
                      <tr><th scope='row'>Stok / Tambah</th>                       <td><input type='number' class='form-control' style='width:100px !important; display:inline-block' value='".($beli['beli']-$jual['jual'])."' disabled>
                                                                              + <input type='number' name='stok' class='form-control' style='width:100px !important; display:inline-block'> </td></tr>
                      <tr><th scope='row'>Merek</th>                    <td><div class='checkbox-scroll'>";
                              $_arrNilai = explode(',', $rows['tag']);
                              foreach ($tag as $tag){
                                  $_ck = (array_search($tag['tag_seo'], $_arrNilai) === false)? '' : 'checked';
                                  echo "<span style='display:block;'><input type=checkbox value='$tag[tag_seo]' name=tag[] $_ck> $tag[nama_tag] &nbsp; &nbsp; &nbsp; </span>";
                              }
                      echo "</div></td></tr>
                      <tr><th scope='row'>Cuplikan</th>                 <td><textarea class='form-control' name='fff' style='height:160px'>$rows[tentang_produk]</textarea></td></tr>
                      <tr><th scope='row'>Aktif</th>                          <td>"; if ($rows['aktif']=='Y'){ echo "<input type='radio' name='aktif' value='Y' checked> Ya &nbsp; <input type='radio' name='aktif' value='N'> Tidak"; }else{ echo "<input type='radio' name='aktif' value='Y'> Ya &nbsp; <input type='radio' name='aktif' value='N' checked> Tidak"; } echo "</td></tr>
                    </tbody>
                  </table>
                </div>

                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th scope='row'>Keterangan</th>                 <td><textarea  id='editor1' class='form-control' name='ff'>$rows[keterangan]</textarea></td></tr>
                    <tr><th scope='row'>Foto Produk</th>                     <td>
                      <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                      <div id='status'></div>";
                      if ($rows['gambar'] != ''){ 
                        $ex = explode(';',$rows['gambar']);
                        $hitungex = count($ex);
                        echo "<span style='color:Red'>File Foto Produk saat ini : </span><br>";
                        for($i=0; $i<$hitungex; $i++){
                            if (file_exists("asset/foto_produk/".$ex[$i])) { 
                                echo "<div class='item' style='border-bottom:1px dotted #cecece; padding-left: 10px;'><a target='_BLANK' href='".base_url()."asset/foto_produk/".$ex[$i]."'>".($i)."). ".$ex[$i]."</a></div>";
                            }
                        }
                      }
                    echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='submit' class='btn btn-info'>Update</button>
                    <a href='#' onclick=\"window.history.go(-1); return false;\"><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
            </div>";
?>
<script>
$(document).ready(function(){
var settings = {
    url: "<?php echo base_url().$this->uri->segment(1); ?>/upload",
    formData: {id: "<?php echo $this->session->id_konsumen; ?>"},
    dragDrop: true,
	  maxFileCount:10,
    fileName: "uploadFile",
	  maxFileSize:5000*1024,
    allowedTypes:"jpg,png,jpeg,gif",		
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDone:false,
    showDelete:true,
    deleteCallback: function(data,pd) {
        $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile",{op: "delete", name:data},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");   
            });
        for(var i=0;i<data.length;i++) {
            $.post("<?php echo base_url().$this->uri->segment(1); ?>/deleteFile",{op:"delete",name:data[i]},
            function(resp, textStatus, jqXHR) {
                // $("#status").append("<div>File Deleted</div>");  
            });
        }   
        pd.statusbar.hide();
    }   
}
$("#mulitplefileuploader").uploadFile(settings);
});
</script>
