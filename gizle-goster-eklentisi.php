<?php
/*
Plugin Name: Yazı Gizle Göster Eklentisi
Description: Yazıları gizlemek ve göstermek için özel bir eklenti.
Version: 1.0
Author: Murat AL
Author URI: https://qnot.net
License: GPL2
*/


class Eklenti_Gizle_Goster {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'eklenti_gizle_goster_metabox'));
        add_action('save_post', array($this, 'eklenti_gizle_goster_kaydet'));
        add_filter('the_content', array($this, 'gizli_yazilari_duzenle'));
        add_filter('get_the_content', array($this, 'gizli_yazi_getir'));
    }

    public function eklenti_gizle_goster_metabox() {
        add_meta_box('gizle-goster-metabox', 'Yazıyı Gizle veya Göster', array($this, 'gizle_goster_metabox_icerik'), 'post', 'side', 'default');
    }

    public function gizle_goster_metabox_icerik($post) {
        $gizli = get_post_meta($post->ID, '_yazi_gizli', true);
        ?>
        <label for="yazi_gizli">Bu yazıyı gizle:
            <input type="checkbox" id="yazi_gizli" name="yazi_gizli" <?php echo $gizli ? 'checked' : ''; ?> />
        </label>
        <?php
    }

    public function eklenti_gizle_goster_kaydet($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['yazi_gizli'])) {
            update_post_meta($post_id, '_yazi_gizli', 1);
        } else {
            delete_post_meta($post_id, '_yazi_gizli');
        }
    }

    public function gizli_yazilari_duzenle($content) {
        if (is_single()) {
            if (!is_user_logged_in()) { // Üye olmayan kullanıcıları kontrol et
                $content = '<h3>Üzgünüz, bu yazı yalnızca üyelere özeldir. Lütfen giriş yapın veya üye olun.</h3>';
            } else {
                $gizli = get_post_meta(get_the_ID(), '_yazi_gizli', true);
                if ($gizli) {
                    return $content;
                }
            }
        }
        return $content;
    }

    public function gizli_yazi_getir($content) {
        if (is_single()) {
            if (!is_user_logged_in()) { // Üye olmayan kullanıcıları kontrol et
                $content = '<h3>Üzgünüz, bu yazı yalnızca üyelere özeldir. Lütfen giriş yapın veya üye olun.</h3>';
            } else {
                $gizli = get_post_meta(get_the_ID(), '_yazi_gizli', true);
                if ($gizli) {
                    return $content;
                }
            }
        }
        return $content;
    }
}

$eklenti_gizle_goster = new Eklenti_Gizle_Goster();
