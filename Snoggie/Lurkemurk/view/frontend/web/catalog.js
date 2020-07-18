define([
    'jquery'
], 
function(jQuery){
    'use strict';
    function main(config, element) {
        jQuery(document).ready(function(){
            // http://127.0.0.1:8083/lurkemurk/#fsck/sck/yodel
            console.log('deeplink: ', window.location.hash.substr(1));
            jQuery.ajax({
                // url: '/lurkemurk/catalog/productsofcategory?ids=12'
                // url: '/lurkemurk/catalog/productsofcategory?ids=12,13'
                // url: '/lurkemurk/catalog/categories'
                // url: 'lurkemurk/catalog/productsbyid?ids=38'
                // url: 'lurkemurk/catalog/productsbyid?ids=38,142'
                // url: 'lurkemurk/catalog/productsbyid?sku=M8-320'
                url: '/lurkemurk/catalog/products',
                type: 'get'
            }).done(function () {
                console.log(arguments[0]);
                return true;
            });
        });
        
    };
    return main;
});