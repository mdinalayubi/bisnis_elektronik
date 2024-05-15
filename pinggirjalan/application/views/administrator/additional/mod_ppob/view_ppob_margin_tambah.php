<?php 
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Margin PPOB</h3>
                </div>
              <div class='box-body'>";
              $attributes = array('class'=>'form-horizontal','role'=>'form');
              echo form_open_multipart('administrator/tambah_ppob_margin',$attributes); 
          echo "<div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value=''>
                    <tr><th width='120px' scope='row'>Operator</th>    <td>
                    <select name='operator' class='form-control' id='operator' required>
                    <option value=''>- Pilih Operator -</option>";
                    foreach($operator->data as $item){
                      if ($item->pembeliankategori_id=='1'){
                        if ($item->status=='0'){ $status = 'disabled'; }else{ $status = ''; }
                        echo "<option value='".$item->id."' $status>".$item->product_name."</option>";
                      }
                    }
                    echo "</select></td></tr>
                    <tr><th scope='row'>Produk</th>                 <td>
                    <select name='produk' class='form-control' id='produk' required>
                      <option value=''>- Pilih Produk -</option>
                    </select></td></tr>
                    <tr><th scope='row'>Jual (Rp)</th>    <td><input type='number' class='form-control' name='margin'></td></tr>
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
  $('#operator').change(function(){
      var operator_id = $(this).val();
      $.ajax({
      type:"POST",
      url:"<?php echo site_url('administrator/produk_ppob'); ?>",
      data:"operator_id="+operator_id,
      success: function(response){
          $('#produk').html(response);
      }
      })
  })
  })
</script>
