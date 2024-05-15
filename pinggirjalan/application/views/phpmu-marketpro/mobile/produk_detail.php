<header class="header header--mobile header--mobile-product" data-sticky="true"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <div class="navigation--mobile">
        <div class="navigation__left"><a class="header__back" href="#" onclick="window.history.go(-1); return false;"><i class="icon-chevron-left"></i><strong>Kembali</strong></a></div>
        <div class="navigation__right">
            <div class="header__actions">

                <?php 
                echo "<div class='ps-cart--mini'><a class='header__extra' href='#'><i class='icon-bag2'></i><span><i class='show_cart_count'></i></span></a>
                        <div class='ps-cart__content'>
                            <div class='ps-cart__items'>
                                <div class='show_cart'></div>
                            </div>
                            <div class='ps-cart__footer'>
                                <div class='show_cart_button'></div>
                            </div>
                        </div>
                    </div>";
                ?>

                <div class="ps-block--user-header">
                    <div class="ps-block__left"><i class="icon-user"></i></div>
                    <div class="ps-block__right"><a href="<?php echo base_url(); ?>auth/login">Login</a><a href="<?php echo base_url(); ?>auth/register">Register</a></div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php 
    echo "<form action='".base_url()."produk/keranjang/$record[id_reseller]/$record[id_produk]' method='POST'>";
?>
    <nav class="navigation--mobile-product">
        <?php 
            if (stok($record['id_reseller'],$record['id_produk'])<=0){ 
                echo "<a style='color:#a7a7a7; background-color:#e2e2e2' class='ps-btn ps-btn--black rounded-0 add-to-cart-empty'>Tambah Keranjang</a>";
            }else{
                echo "<a style='color:#fff' id='$record[id_produk]' class='ps-btn ps-btn--black rounded-0 add-to-cart'>Tambah Keranjang</a>";
            }
        ?>

        <input class="form-control" type="hidden" value='1' name='qty'>
        <button type='submit' name='beli' class="ps-btn btn-block rounded-0">Beli Sekarang</button>
    </nav>
</form>
                        