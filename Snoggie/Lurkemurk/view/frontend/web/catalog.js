define([
    'jquery'
], 

function(jQuery){ 'use strict';

    function main(config, element) {

        var CP = {};
        
        jQuery(document).ready(function(){
            
            CP.load = function(){
                
                jQuery.ajax({
                    url: arguments[0],
                    type: 'get'
                }).done(function () {
                    console.log(arguments[0]);
                    return true;
                });
            }
            
            CP.post = function(){
                
                jQuery.ajax({
                    url: arguments[0],
                    data: arguments[1],
                    type: 'post'
                }).done(function () {
                    console.log(arguments[0]);
                    return true;
                });
            }
            
            CP.evalDeepLink = function(){
                // entry point of the *app
                // http://127.0.0.1:8083/lurkemurk/#cat-name/prod-name/view-name...
                // as for javascript data driven view *manipulation
                console.log('deeplink: ', window.location.hash.substr(1));
                // ...dunno...
            }
            
            // carefull.. this *test calls is not asiNC...
            CP.evalDeepLink();

            CP.load('/lurkemurk/catalog/categoriesbyid?ids=8');
            CP.load('/lurkemurk/catalog/products');
            CP.load('/lurkemurk/catalog/productsofcategory?ids=12');
            CP.load('/lurkemurk/catalog/productsofcategory?ids=12,13');
            CP.load('/lurkemurk/catalog/categories');
            CP.load('/lurkemurk/catalog/productsbyid?ids=38');
            CP.load('/lurkemurk/catalog/productsbyid?ids=38,142');
            // ---
            CP.post('/lurkemurk/customer/auth', {'clnt':'lemmey', 'pass':'login'});
            // 
            CP.post('/lurkemurk/cart/add', {'pid':'8', 'qty':'3'});
            CP.post('/lurkemurk/cart/remove', {'pid':'8', 'qty':'2'});
            // 
            CP.load('/lurkemurk/cart/get');    
        });
    };

    return main;
});