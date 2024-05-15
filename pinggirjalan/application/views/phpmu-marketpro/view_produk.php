<?php 
foreach($produk->data as $item){
    if ($item->pembelianoperator_id==$operator_id){
        if ($item->status=='0'){ $status = 'disabled'; }else{ $status = ''; }
        $cek_margin = $this->db->query("SELECT * FROM rb_ppob_margin where kode_ppob='".$item->code."'");
        if ($cek_margin->num_rows()>='1'){
            $row = $cek_margin->row_array();
            echo "<option value='".$item->code."|".($item->price+$row['margin'])."' $status>".$item->product_name." (Rp ".rupiah($item->price+$row['margin']).")</option>";
        }else{
            echo "<option value='".$item->code."|".$item->price."' $status>".$item->product_name." (Rp ".rupiah($item->price).")</option>";
        }
    }
}