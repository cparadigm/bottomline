function deleteCartInCheckoutPage(){ 

    $jq(".checkout-cart-index a.btn-remove2,.checkout-cart-index a.btn-remove").click(function(event) {
        event.preventDefault();
               if(!confirm(confirm_content)){
            return false;
        }
   
        
    });
   
    return false;
}
function slideEffectAjax() {
      $jq('.top-cart-contain').mouseenter(function() {
            $jq(this).find(".top-cart-content").stop(true, true).slideDown();
        });

        $jq('.top-cart-contain').mouseleave(function() {
            $jq(this).find(".top-cart-content").stop(true, true).slideUp();
        });
}
function deleteCartInSidebar() {

    if(is_checkout_page>0) return false;
    $jq('#cart-sidebar a.btn-remove, #mini_cart_block a.btn-remove').each(function(){});
}  

$jq(document).ready(function(){
    slideEffectAjax();
});


/*-------- End Cart js -------------------*/