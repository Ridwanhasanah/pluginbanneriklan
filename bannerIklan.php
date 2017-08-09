<?php
/*
  Plugin Name: Banner Iklan
  Plugin URI: https://www.facebook.com/ridwan.hasanah3
  Description: Menamahkan Banner Iklan
  Version: 1.0
  Author: Ridwan Hasanah
  Author URI: https://www.facebook.com/ridwan.hasanah3
*/
 
 
/*
  Banner Iklan Start
*/
  add_filter("the_content","rh_banner_iklan" ); //the_content untuk menampilkan hasil di content  page, post
 
  /*
  uncomment function ini jika ingin menampilkan iklan di setiap singel post
  function rh_banner_iklan($content){  
 
 
    if (is_singular('post' )) { //ini di gunakan untuk menyaring agar tampil di post, 
      //jika ingin tampil di page cukup di hilangkan kondisi if nya saja
      $image = "http://localhost/wordpress/wp-content/uploads/2015/09/Untitled.png";
      return '<a target="_blank" href="https://www.facebook.com/ridwan.hasanah3"><img src="'.$image.'">'.$content.'</a>';      
    }else{
      return $content;
    }
    
     
  }*/
 
  function rh_banner_iklan($content){
 
    $options   = get_option("rh-banner-iklan-option-fields");
    
     
    if (is_singular('post') || is_singular('page' )) {
 
      //$image = "http://localhost/wordpress/wp-content/uploads/2015/09/Untitled.png";
      //$after_paragraph = 1;
      $posisi    = $options['rh-banner-iklan-posisi'];
      $paragraf  = $options['rh-banner-iklan-paragraf'];
      //$url_image = $options['rh-banner-iklan-url-image'];
      $iklan     = html_entity_decode($options['rh-banner-iklan-kode-iklan']);
      $halaman   = explode(',', $options['rh-banner-iklan-halaman']);
 
      if (is_singular('post' )) {
        if (!in_array('Single Post',$halaman)) {
          return $content;
        }
      }
 
      if (is_singular('page' )) {
          if (!in_array('Single Page', $halaman)) {
            return $content;
          }
      }
 
      switch ($posisi) {
        case 'Sebelum Konten':
          return $content.$iklan;
          break;
        case 'Setelah Konten':
          return $content.$iklan;
          break;
 
        case 'Setelah Paragraf':
          $after_paragraph = $paragraf;
          $paragraphs = explode('<p>',$content);
          if (count($paragraphs) >= $after_paragraph) {
            foreach ($paragraphs as $index => $paragraph) {
              if (trim($paragraph)) {
                $paragraphs[$index].= '</p>';
              }
            }
            $paragraphs[$after_paragraph-1].='<img src="'.$url_image.'">';
            return implode($paragraphs);
          }else{
            return $content.$iklan;
          }
        default:
          return $content;//.'<img src="'.$image.'">';
      }
    }else{
      return $content;
    }
 
    //if (is_singular('post' )) {
      //$paragraphs = explode('</p>', $content);
      /*explode berguna untuk memisahkan string karna di sini mengunakan <p> maka akan di pisahkan 
       per paragraf*/
 
      //if (count($paragraphs) >= $after_paragraph) { //menghitung paragraf jk paragraf lebih dari 1 maka...
        //foreach ($paragraphs as $index => $paragraph) { 
          /*melooping paragraf karna sudah di pisah menggunakan explode maka di tampung oleh varibale $index*/
          //if (trim($paragraph)) { //trim berguna untuk menghapus spasi atau karakter
            //$paragraphs[$index].='</p>';
          //}
        //}
        //$paragraphs[$after_paragraph-1].='<a target="_blank" href="https://www.facebook.com/ridwan.hasanah3"><img src="'.$image.'"></a>';
        //return implode($paragraphs); 
        /*implode untuk mengabungkan string yag terpisah oleh explode tadi*/
 
      //}else{
        //return $content.'<img src="'.$image.'">';
     // }
    //}else{
      //return $content;
    //}
  }
 
/*
  Banner Iklan END
*/
 
 
/*
  Banner Iklan Menu Start
*/
  function rh_banner_iklan_menu(){
    add_menu_page(
      'Banner Iklan',
      'Banner Iklan',
      'manage_options',
      'banner-iklan',
      'rh_banner_iklan_options' );
  }
 
  add_action('admin_menu','rh_banner_iklan_menu' );
 
/*
  Banner Iklan Menu End
*/
 
 
  /*
    Banner Iklan Option Start
 
    Function ini untuk setting yang bearada di menu Banner iklan
 
  */
 
  function rh_banner_iklan_options(){
    echo "<h2>Banner Iklan</h2>";
 
    if (is_null($_POST['rh-banner-iklan-halaman'])) {
      $_POST['rh-banner-iklan-halaman'] = array();
    }
 
    if ($_POST['rh-banner-iklan-submit']) {
      $options['rh-banner-iklan-posisi']     = $_POST['rh-banner-iklan-posisi'];
      $options['rh-banner-iklan-paragraf']   = $_POST['rh-banner-iklan-paragraf'];
     // $options['rh-banner-iklan-url-image']  = $_POST['rh-banner-iklan-url-image'];
      $options['rh-banner-iklan-kode-iklan'] = htmlentities(stripcslashes($_POST['rh-banner-iklan-kode-iklan']));
      /*Perintah penggunaan stripcslashes() utk menghilankan tanda blackslash 
        Perintah penggunaan htmlentities() utk merubah karakter dalam html menjadi HTML entity*/
      $options['rh-banner-iklan-halaman']    = implode(',', $_POST['rh-banner-iklan-halaman']);
      update_option("rh-banner-iklan-option-fields", $options );
      echo '<div class="updated"><p><strong>Options Saved.</strong></p></div>';
    }
 
    $options    = get_option("rh-banner-iklan-option-fields");
    $posisi     = $options['rh-banner-iklan-posisi'];
    $paragraf   = $options['rh-banner-iklan-paragraf'];
   // $url_image = $options['rh-banner-iklan-url-image'];
    $kode_iklan = html_entity_decode($options['rh-banner-iklan-kode-iklan']);
    $halaman    = explode(',', $options['rh-banner-iklan-halaman']);
  ?>
 
  <!-- Contoh SEtting dalam Bentuk SElect Option dan Menambahkan Kode Iklan Start-->
  <label><h3>Menggunakan Kode Html</h3></label>
  <form method="post">
    <table class="form-table">
      <tr>
        <td width="100px">
          <label for="rh-banner-iklan-posisi">Posisi Iklan</label>
        </td>
        <td>
          <select id="rh-banner-iklan-posisi" name="rh-banner-iklan-posisi" >
            <option value="Sebelum Konten" <?php if ($posisi == 'Sebelum Konten')
              echo 'selected'; ?>>Sebelum Konten</option>
 
            <option value="Setelah Konten" <?php if ($posisi == 'Setelah Konten')
              echo 'selected'; ?>>Setelah Konten</option>
 
            <option value="Setelah Paragraf" <?php if ($posisi == 'Setelah Paragraf')
              echo 'selected'; ?>>Setelah Paragraf</option>
                   
          </select>
          <input type="text" size="2" id="rh-banner-iklan-paragraf" value="<?php if ($posisi == 'Setelah Paragraf') echo $paragraf;?>" name="rh-banner-iklan-paragraf">
        </td>
      </tr>
      <tr>
        <td>
          <label for="rh-banner-iklan-kode-iklan">Kode Iklan</label>
        </td>
        <td>
          <textarea cols="50" rows="5" name="rh-banner-iklan-kode-iklan" id="rh-banner-iklan-kode-iklan"><?php echo $kode_iklan; ?></textarea>
        </td>
      </tr>
      <tr>
        <td><label for="rh-banner-iklan-halaman">Tampil Pada</label></td>
        <td>
          <input type="checkbox" id="rh-banner-iklan-halaman1" name="rh-banner-iklan-halaman[]" value="Single Post" <?php if (in_array('Single Post', $halaman)) echo 'checked';?>>Single Post <br>
 
          <input type="checkbox" id="rh-banner-iklan-halaman2" name="rh-banner-iklan-halaman[]" value="Single Page" <?php if (in_array('Single Page', $halaman)) echo 'checked';?>>Single Page <br>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
          <input type="submit" name="rh-banner-iklan-submit" id="rh-banner-iklan-submit" value="Simpan" class="button">
        </td>
      </tr>
    </table>
  </form>
  <hr>
   <!-- Contoh SEtting dalam Bentuk SElect Option dan Menambahkan Kode Iklan End-->
  
 
 
  <?php
   }
 
   /*
    Banner Iklan Option End
  */
?>