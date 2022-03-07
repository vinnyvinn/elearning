<?php



?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 text-center">
                <h3 class="uppercase color-primary mb40 mb-xs-24">CONTACT US</h3>
                <p class="lead">Feel free to contact us via&nbsp;</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php if (get_option('logo1'))?>
                <img alt="Logo1"   src="<?php echo base_url('uploads/files/'.get_option('logo1')); ?>" width="262">
            </div>
            <div class="col-md-3">
                <h2 style="margin-bottom: 0" >Address:</h2>
                <?php
                $phone1 = get_option('phone1') ? json_decode(get_option('phone1')) : '';
                ?>
                <?php if (is_array($phone1)):
                    foreach ($phone1 as $p):
                        ?>
                        <p style="margin-top: 1px;margin-bottom: 1px"><?php echo $p;?></p>
                    <?php endforeach;endif;?>


                        <p style="margin-top: 1px;margin-bottom: 1px"><?php echo get_option('address1');?></p>
            </div>
                <div class="col-md-3">
                    <?php if (get_option('logo2'))?>
                    <img alt="Logo1"   src="<?php echo base_url('uploads/files/'.get_option('logo2')); ?>" width="262">
                </div>
                <div class="col-md-3">
                    <h2 style="margin-bottom: 0" >Address:</h2>
                    <?php
                    $phone2 = get_option('phone2') ? json_decode(get_option('phone2')) : '';?>
                    <?php if (is_array($phone2)):
                        foreach ($phone2 as $p):
                            ?>
                            <p style="margin-top: 1px;margin-bottom: 1px"><?php echo $p;?></p>
                        <?php endforeach;endif;?>
                            <p style="margin-top: 1px;margin-bottom: 1px"><?php echo get_option('address2');?></p>
            </div>
        </div>
        <div class="row">

           <div class="col-md-4 mb7">
              <p class="mb0">
                  <a class="btn btn-primary br50" href="<?php  echo (get_option('facebook_link') ? get_option('facebook_link') :'#')?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                          <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                      </svg>
                      </i></a>
              </p>
               <p class="mb0">
                   <a class="btn btn-warning" href="<?php  echo (get_option('telegram_link') ? get_option('telegram_link') :'#')?>">
                       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                           <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                       </svg>
                       </i></a>
               </p>
               <p>
                   <a class="btn btn-default" href="<?php  echo (get_option('youtube_link') ? get_option('youtube_link') :'#')?>">
                       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-youtube" viewBox="0 0 16 16">
                           <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
                       </svg>
                       </i></a>
               </p>
           </div>
            <?php $address = str_replace(" ", "+", get_option('website_location') ? get_option('website_location') : 'nairobi');?>
            <div class="col-md-8 p0 image-square image mb7">
                <iframe width="100%" height="500" src="https://maps.google.com/maps?q=<?php echo $address;?>&output=embed"></iframe>
            </div>


              </div>

    </div>

</section>