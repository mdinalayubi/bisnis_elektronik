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
                  <h3 class='box-title'>Tambah Produk Baru</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form','name'=>'demo');
              echo form_open_multipart('administrator/tambah_produk',$attributes); 
          echo "<div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                    <tr><th width='130px'scope='row'>Pemilik</th>                   <td><select style='color:red' name='id_reseller' class='form-control combobox' required>
                                        <option value='0' selected>Perusahaan</option>";
                                        $reseller = $this->db->query("SELECT * FROM rb_reseller");
                                        foreach ($reseller->result_array() as $row){
                                            echo "<option value='$row[id_reseller]'>$row[nama_reseller]</option>";
                                        }
                    echo "</td></tr>
                    <tr><th scope='row'>Kategori</th>                   <td><select name='a' class='form-control' onchange=\"showSub()\" required>
                                        <option value='' selected>- Pilih Kategori Produk -</option>";
                                        foreach ($record as $row){
                                            echo "<option value='$row[id_kategori_produk]'>$row[nama_kategori]</option>";
                                        }
                    echo "</td></tr>
                    <tr><th scope='row'>Sub Kategori</th>                   <td><select name='aa' class='form-control' id='sub_kategori_produk'>
                                          <option value='' selected>- Pilih Sub Kategori Produk -</option>
                                        </td></tr>
                    <tr><th scope='row'>Nama Produk</th>  <td><input type='text' class='form-control' name='b' required></td></tr>
                    <tr><th scope='row'>Satuan</th>                     <td><input type='text' class='form-control' name='c'></td></tr>
                    <tr><th scope='row'>Berat / Gram</th>                      <td><input type='number' class='form-control' name='berat'></td></tr>
                    <tr><th width='130px' scope='row'>Harga Modal</th>                 <td><input type='text' class='form-control formatNumber' name='d'></td></tr>
                    <tr><th scope='row'>Harga Reseller</th>             <td><input type='text' class='form-control formatNumber' name='e'></td></tr>
                    <tr><th scope='row'>Harga Konsumen</th>             <td><input type='text' class='form-control formatNumber' name='f'></td></tr>
                    </tbody>
                  </table>
                </div>

                <div class='col-md-6 col-xs-12'>
                  <table class='table table-condensed'>
                    <tbody>
                      <tr><th scope='row'>Diskon</th>                 <td><input type='text' class='form-control formatNumber' name='diskon'></td></tr>
                      <tr><th scope='row'>Stok Awal</th>             <td><input type='number' name='stok' class='form-control'> </td></tr>
                      <tr><th scope='row'>Merek</th>                    <td><div class='checkbox-scroll'>";
                                                                            foreach ($tag as $tag){
                                                                                echo "<span style='display:block;'><input type=checkbox value='$tag[tag_seo]' name=tag[]> $tag[nama_tag] &nbsp; &nbsp; &nbsp; </span>";
                                                                            }
                      echo "</div></td></tr>
                      <tr><th scope='row'>Cuplikan</th>                 <td><textarea class='form-control' name='fff' style='height:140px'></textarea></td></tr>
                      <tr><th scope='row'>Aktif</th>                          <td><input type='radio' name='aktif' value='Y' checked> Ya &nbsp; <input type='radio' name='aktif' value='N'> Tidak</td></tr>
                    </tbody>
                  </table>
                </div>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th scope='row'>Keterangan</th>                 <td><textarea id='editor1' class='form-control' name='ff'></textarea></td></tr>
                    <tr><th scope='row'>Foto Produk</th>                     <td>
                      <div id='mulitplefileuploader' class='mt-2'>Choose files</div>
                      <div id='status'></div>
                    </td></tr>
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
