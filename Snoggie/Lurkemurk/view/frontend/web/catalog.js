define([
    'jquery'
], 
function(jQuery){
    'use strict';
    function main(config, element) {
        var CP = {};
        jQuery(document).ready(function(){
            CP.load = function(){
                // loading blocks might speed up the fetch dunno...
                jQuery.ajax({
                    url: arguments[0],
                    type: 'get'
                }).done(function () {
                    console.log(arguments[0]);
                    return true;
                });
            }
            CP.evalDeepLink = function(){
                // entry point of the *app
                // http://127.0.0.1:8083/lurkemurk/#fsck/sck/yodel
                console.log('deeplink: ', window.location.hash.substr(1));
            }
            CP.load('/lurkemurk/cart/add?ids=8');
            CP.load('/lurkemurk/catalog/productsofcategory?ids=12');
            CP.load('/lurkemurk/catalog/productsofcategory?ids=12,13');
            CP.load('/lurkemurk/catalog/categories');
            CP.load('/lurkemurk/catalog/productsbyid?ids=38');
            CP.load('/lurkemurk/catalog/productsbyid?ids=38,142');
            CP.load('/lurkemurk/catalog/products');
            CP.load('/lurkemurk/catalog/categoriesbyid?ids=8');
        });
    };
    return main;
});