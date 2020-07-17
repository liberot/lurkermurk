define([
    'jquery'
], 
function(jQuery){
    'use strict';
    function main(config, element) {
        jQuery(document).ready(function(){
            jQuery.ajax({
                context: "#resp",
                // url: '/lurkemurk/catalog/productsofcategory?ids=12'
                // url: '/lurkemurk/catalog/productsofcategory?ids=12,13'
                // url: '/lurkemurk/catalog/categories',
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