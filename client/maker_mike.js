(function ($, window, undefined) {
    $.mm_config = {}
    $.mm_set = function (params) {
        Object.assign($.mm_config, params);
    };
    $.mm_get = function (params) {
        if(params)
            $.mm_set(params);
        return new Promise(function(fullfill, reject){
            // todo missing show, query, etc..
            $.get('/src/crud_read.php?project='+$.mm_config['project']+'&table='+$.mm_config['table']+'', function(data){
                fullfill(JSON.parse(data));
            })
        });
    };
    $.mm_set = function (params) {
        if(params)
            $.mm_set(params);
        return new Promise(function(fullfill, reject){
            $.get('/src/crud_read.php?project='+$.mm_config['project']+'&table='+$.mm_config['table']+'', function(data){
                fullfill(JSON.parse(data));
            })
        });
    };
    $.mm_patch = function (params) {
        if(params)
            $.mm_set(params);
        return new Promise(function(fullfill, reject){
            $.get('/src/crud_read.php?project='+$.mm_config['project']+'&table='+$.mm_config['table']+'', function(data){
                fullfill(JSON.parse(data));
            })
        });
    };
})(jQuery, window);


// Example of usage
$.mm_get({'project': 'miguelp', 'table':'portlet'});