define(

[ 'jquery' ], 




function(jQuery){ 'use strict'; 
    function main(config, element) {

        var CP = {};
        
        jQuery(document).ready(function(){
            
            CP.evalLink = function(){
                // entry point of the *app
                // http://127.0.0.1:8083/lurkemurk/#cat-name/prod-name/view-name...
                // as for javascript data driven view *manipulation
                console.log('link: ', window.location.hash.substr(1));
                // ...dunno...
            }

            CP.setLink = function(chunk){
               
                /* 
                var lnk = '';
                    lnk = window.location.href;
                    lnk = lnk.replace(/#.{1,255}/, '');
                    lnk = lnk.replace(/\/+#.{1,255}/, '');
                    lnk = lnk.replace(/\/+$/, '');
                    lnk = lnk +'#' +chunk; 

                window.location.href = lnk;
                */

                var lnk = '';
                    lnk = window.location.href.substr(1);
                if(lnk.match(/\/+$/)){
                    chunk = chunk.replace(/^\//, '');
                }
                window.location.hash = chunk;
            }
            
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
            
            // carefull.. this *test calls is not asiNC...
            
            CP.load('/lurkemurk/catalog/categoriesbyid?ids=8');
            CP.load('/lurkemurk/catalog/products');
            CP.load('/lurkemurk/catalog/productsofcategory?ids=12');
            CP.load('/lurkemurk/catalog/productsofcategory?ids=12,13');
            CP.load('/lurkemurk/catalog/categories');
            CP.load('/lurkemurk/catalog/productsbyid?ids=38');
            CP.load('/lurkemurk/catalog/productsbyid?ids=38,142');
            // ---
            CP.post('/lurkemurk/customer/auth', {'clnt':'lemmey', 'pass':'login'});
            CP.post('/lurkemurk/customer/auth', {'clnt':'email@domain.com', 'pass':'password'});
            CP.post('/lurkemurk/customer/register', {'clnt': 'fako@igit.com', 'pass':'31affe', 'forename':'Fako', 'surename': 'Loco'});
            // 
            CP.post('/lurkemurk/cart/truncate');
            CP.post('/lurkemurk/cart/put', {'pid':'8', 'qty':'3'});
            CP.post('/lurkemurk/cart/remove', {'pid':'8', 'qty':'2'});
            // 
            CP.load('/lurkemurk/cart/listitems');
        
            CP.setLink('/laufen/indoor/adidas-manhattan');
            CP.evalLink();
        });
    };

    return main;
});