<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

remove_image_size( 'thumbnail' );
remove_image_size( 'medium' );
remove_image_size( 'medium_large' );
remove_image_size( 'large' );
remove_image_size( '1536x1536' );
remove_image_size( '2048x2048' );
remove_image_size( '2560×2560' );

add_image_size( 'banner size', 1920, 600 );
add_image_size( 'banner mobile size', 1080, 1080 );
add_image_size( 'produto size', 500, 500 );
add_image_size( 'produto size table', 600, 600 );
add_image_size( 'produto size 2', 1080, 1080 );
add_image_size( 'micro banners size', 430, 260 );

add_filter( 'wc_add_to_cart_message_html', '__return_false' );
add_filter( 'woocommerce_notice_types', '__return_empty_array' );


function my_yith_wcas_submit_label( $label ) { 
    return '' . $label; 
}

add_filter( 'yith_wcas_submit_as_input', '__return_false' );
add_filter( 'yith_wcas_submit_label', 'my_yith_wcas_submit_label' );

/**
 * @snippet       Pay for Order if Logged Out - WooCommerce Order Pay
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
// add_filter( 'user_has_cap', 'bbloomer_order_pay_without_login', 9999, 3 );

add_filter( 'user_has_cap', 'aireset_order_pay_without_login', 9999, 3 );

if(!function_exists('aireset_order_pay_without_login')){
	function aireset_order_pay_without_login( $allcaps, $caps, $args ) {
	   if ( isset( $caps[0], $_GET['key'] ) ) {
		  if ( $caps[0] == 'pay_for_order' ) {
			 $order_id = isset( $args[2] ) ? $args[2] : null;
			 $order = wc_get_order( $order_id );
			 if ( $order ) {
				$allcaps['pay_for_order'] = true;
			 }
		  }
	   }
	   return $allcaps;
	}
}
 
add_filter( 'woocommerce_order_email_verification_required', '__return_false', 9999 );

add_filter( 'option_active_plugins', function ( $wp_enabled_plugins ) {
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        // Aqui você remove o plugin JetElements da lista de plugins ativos
        $key = array_search( 'jet-elements/jet-elements.php', $wp_enabled_plugins );
        if ( false !== $key ) {
            unset( $wp_enabled_plugins[$key] );
        }
    }
    return $wp_enabled_plugins;
});

function add_custom_order_status() {
    register_post_status( 'wc-payed', array(
        'label'                     => 'Pedido Pago',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Pedidos Pagos <span class="count">(%s)</span>', 'Pedidos Pago <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-partial-payed', array(
        'label'                     => 'Pedido Pago Parcialmente',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Pedidos Pagos Parcialmente <span class="count">(%s)</span>', 'Pedidos Pago Parcialmente <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-order-sended', array(
        'label'                     => 'Pedido Enviado',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Pedidos Enviados <span class="count">(%s)</span>', 'Pedidos Enviados <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-in-separation', array(
        'label'                     => 'Em Separação',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Em Separação <span class="count">(%s)</span>', 'Em Separação <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'add_custom_order_status' );

function edit_default_order_status_titles( $order_statuses ) {
    // Editando os status padrões
    if ( isset( $order_statuses['wc-on-hold'] ) ) {
        $order_statuses['wc-on-hold'] = 'Aguardando Confirmação'; // Novo título para 'on-hold'
    }
    if ( isset( $order_statuses['wc-pending'] ) ) {
        $order_statuses['wc-pending'] = 'Pagamento Pendente'; // Novo título para 'pending'
    }
    if ( isset( $order_statuses['wc-processing'] ) ) {
        $order_statuses['wc-processing'] = 'Processando'; // Novo título para 'pending'
    }
    if ( isset( $order_statuses['wc-cancelled'] ) ) {
        $order_statuses['wc-cancelled'] = 'Cancelado'; // Novo título para 'pending'
    }
    if ( isset( $order_statuses['wc-order-completed'] ) ) {
        $order_statuses['wc-order-completed'] = 'Concluído'; // Novo título para 'pending'
    }
    if ( isset( $order_statuses['wc-order-refunded'] ) ) {
        $order_statuses['wc-order-refunded'] = 'Reembolsado'; // Novo título para 'pending'
    }
    if ( isset( $order_statuses['wc-order-failed'] ) ) {
        $order_statuses['wc-order-failed'] = 'Malsucedido'; // Novo título para 'pending'
    }
	
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-payed'] = 'Pedido Pago';
            $new_order_statuses['wc-partial-payed'] = 'Pedido Pago Parcialmente';
            $new_order_statuses['wc-order-sended'] = 'Pedido Enviado';
            $new_order_statuses['wc-in-separation'] = 'Em Separação';
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'edit_default_order_status_titles' );

function custom_order_status_color() {
    ?>
    <style>
        .order-status.status-wc-on-hold { background: #f7a400; }
        .order-status.status-wc-pending { background: #3d3d3d; }
        .order-status.status-wc-processing { background: #cccc00; }
        .order-status.status-wc-cancelled { background: #e50000; }
        .order-status.status-wc-payed { background: #44ce00; }
        .order-status.status-wc-partial-payed { background: #44ce00; }
        .order-status.status-wc-in-separation { background: #214dd1; }
        .order-status.status-wc-order-sended { background: #00c130; }
        .order-status.status-wc-order-completed { background: #007fe0; }
        .order-status.status-wc-order-refunded { background: #898989; }
        .order-status.status-wc-order-failed { background: #ea4f4f; }		
    </style>
    <?php
}
add_action('admin_head', 'custom_order_status_color');

function add_custom_order_status_bulk_actions($bulk_actions) {
    $bulk_actions['mark_on-hold'] = 'Marcar como aguardando';
    $bulk_actions['mark_pending'] = 'Marcar como pendente';
    $bulk_actions['mark_processing'] = 'Marcar como processando';
    $bulk_actions['mark_cancelled'] = 'Marcar como cancelado';	
    $bulk_actions['mark_payed'] = 'Marcar como pago';
    $bulk_actions['mark_partial_payed'] = 'Marcar como pago parcialmente';
    $bulk_actions['mark_in-separation'] = 'Marcar como em separação';
    $bulk_actions['mark_order-sended'] = 'Marcar como pedido enviado';
    $bulk_actions['mark_order-completed'] = 'Marcar como concluído';
	$bulk_actions['mark_order-refunded'] = 'Marcar como reembolsado';
	$bulk_actions['mark_order-failed'] = 'Marcar como falho';
    return $bulk_actions;
}
add_filter('bulk_actions-edit-shop_order', 'add_custom_order_status_bulk_actions');


function include_custom_order_status_in_reports( $order_statuses ) {
    $order_statuses[] = 'wc-payed'; // Adiciona o status personalizado aos relatórios
    $order_statuses[] = 'wc-partial-payed'; // Adiciona o status personalizado aos relatórios
    $order_statuses[] = 'wc-order-sended'; // Adiciona o status personalizado aos relatórios
    $order_statuses[] = 'wc-in-separation';
    return $order_statuses;
}
add_filter( 'woocommerce_reports_order_statuses', 'include_custom_order_status_in_reports' );



// function yasglobal_redundant_hyphens() {
//   return true;
// }
// add_filter( 'custom_permalinks_redundant_hyphens', 'yasglobal_redundant_hyphens' );

// function yasglobal_permalink_before_saving( $permalink, $post_id ) {
//   // Check trialing slash in the permalink.
//   if ( substr( $permalink, -1 ) !== '/' ) {
//     // If permalink doesn't contain trialing slash then add one.
//     $permalink .= '/';
//   }

//   return $permalink;
// }
// add_filter( 'custom_permalink_before_saving', 'yasglobal_permalink_before_saving', 10, 2 );

/*
if(!function_exists('custom_orders_list_column_content')){
    add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 50, 2 );
    
    function custom_orders_list_column_content( $column, $post_id ) {
        if ( $column == 'order_number' )
        {
            global $the_order;
    
            if( $phone = $the_order->get_billing_phone() ){
                $phone_wp_dashicon = '<span class="dashicons dashicons-phone"></span> ';
                echo '<br>Celular: '.'<a href="tel:'.$phone.'" target="_blank">' . $phone.'</a></strong>';
                $status = wc_get_order_status_name($the_order->get_status());
                $text = urlencode("Olá ". $the_order->get_billing_first_name() .", tudo bem? ". PHP_EOL ."Vimos que você fez o pedido ". $the_order->get_id() ." que está como ". $status . ", conte para nós como podemos te ajudar?");
                echo '<br>Whatsapp: '.'<a href="https://wa.me/55'.preg_replace("/[^0-9]/", "",$phone).'?text='. $text .'" target="_blank">' . $phone.'</a></strong>';
            }
    
            if( $email = $the_order->get_billing_email() ){
                echo '<br>Email: '.'<a href="mailto:'.$email.'" target="_blank">' . $email . '</a>';
            }
        }
    }
}

function simulador_produto_frenet(){
	?>
	<div id="productPageSimulator"><?=WC_Frenet_Shipping_Simulator::simulator()?></div>
	<style>
		body #productPageSimulator {}
		body #productPageSimulator #shipping-simulator {
			width: 100%;
			display: inline-block;
		}
		body #productPageSimulator #shipping-simulator form {}
		body #productPageSimulator #shipping-simulator form label {
			width: 100%;
			display: inline-block;
			margin: 0;
			padding: 15px 0;
			color: var( --e-global-color-secondary );
			font-family: "Bechtlers", Montserrat,sans-serif;
			font-size: 18px;
			font-weight: 600;
			text-transform: capitalize;
			line-height: 18px;
			letter-spacing: 0.3px;
		}
		body #productPageSimulator #shipping-simulator form input#zipcode {
			min-width: 200px;
			display: inline-block;
			margin: 0;
			padding: 15px 15px;
			border-radius: 15px 0 0 15px;
			color: var( --e-global-color-secondary );
			font-family: "Bechtlers", Montserrat,sans-serif;
			font-size: 16px;
			font-weight: 600;
			text-transform: uppercase;
			line-height: 16px;
			letter-spacing: 0.3px;
			height: 52px;
			border-color: var( --e-global-color-secondary );
		}
		body #productPageSimulator #shipping-simulator form input#zipcode::-webkit-input-placeholder
		{
			color: var( --e-global-color-secondary );
			font-family: "Bechtlers", Montserrat,sans-serif;
			font-size: 16px;
			font-weight: 600;
			text-transform: uppercase;
			line-height: 16px;
			letter-spacing: 0.3px;
		}
		body #productPageSimulator #shipping-simulator form input#zipcode:-ms-input-placeholder
		{
			color: var( --e-global-color-secondary );
			font-family: "Bechtlers", Montserrat,sans-serif;
			font-size: 16px;
			font-weight: 600;
			text-transform: uppercase;
			line-height: 16px;
			letter-spacing: 0.3px;
		}
		body #productPageSimulator #shipping-simulator form input#zipcode::placeholder 
		{
			color: var( --e-global-color-secondary );
			font-family: "Bechtlers", Montserrat,sans-serif;
			font-size: 16px;
			font-weight: 600;
			text-transform: uppercase;
			line-height: 16px;
			letter-spacing: 0.3px;
		}
		body #productPageSimulator #shipping-simulator form button#idx-calc_shipping {
			font-size: 16px;
			font-weight: 600;
			text-transform: uppercase;
			line-height: 16px !important;
			border-radius: 8px 8px 8px 8px;  
			color: #FFFFFF;
			background-color: var( --e-global-color-primary );
			padding: 16px 25px 16px 25px !important;
			margin: 0px 15px 0px 0px !important;
			border-radius: 0 15px 15px 0;
			height: 52px;
		}
		body #productPageSimulator #shipping-simulator form #simulator-data {
		}
		body #productPageSimulator #shipping-simulator form #simulator-data  #shipping-rates {
			border: 1px solid var( --e-global-color-secondary );
			border-radius: 15px;
			padding: 15px 15px;
		}
		body #productPageSimulator #shipping-simulator form #simulator-data li {
			width: 100%;
			display: inline-block;
			margin: 0;
			padding: 15px 0;
			color: var( --e-global-color-secondary );
			font-family: "Bechtlers", Montserrat,sans-serif;
			font-size: 18px;
			font-weight: 600;
			text-transform: capitalize;
			line-height: 18px;
			letter-spacing: 0.3px;
		}
	</style>

	<script>
		jQuery(function($){
			var updateVar = setTimeout(function() {}, 1);
			$(document).on('click', '.shopengine-qty-btn .plus', function () {
				$('#productPageSimulator .qty_simulator').attr('value', $('.quantity .qty').val());
				if(!$('#updateVar #simulator-data').is(':empty')){
					clearTimeout(updateVar);
					updateVar = setTimeout(function() {
						$('#productPageSimulator #simulator-data').html();
						$('#productPageSimulator #idx-calc_shipping').trigger('click');
					}, 1000);
				}
			});
			$(document).on('click', '.shopengine-qty-btn .minus', function () {
				$('#productPageSimulator .qty_simulator').attr('value', $('.quantity .qty').val());
				if(!$('#productPageSimulator #simulator-data').is(':empty')){
					clearTimeout(updateVar);
					updateVar = setTimeout(function() {
						$('#productPageSimulator #simulator-data').html();
						$('#productPageSimulator #idx-calc_shipping').trigger('click');
					}, 1000);
				}
			});
			
			$("#productPageSimulator label").after($("#zipcode"))
			
			$("#productPageSimulator label").after($("#productPageSimulator #zipcode"))
		})
	</script>
	<?php
}

add_shortcode( 'simulador_produto_frenet', 'simulador_produto_frenet' );

function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('view-site');        // Remove the view site link
    $wp_admin_bar->remove_menu('view-store');        // Remove the view site link
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

*/