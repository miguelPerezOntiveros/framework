(function ($, window, undefined) {
    $.mm = {};
    $.mm.init = function (project) {
        if(project){
            $.mm[project] = {};
            return new Promise(function(fullfill, reject){
                $.get('/projects/'+project+'/admin/discovery.php', function(data){
                    data = JSON.parse(data);
                    for(table in data){
                        $.mm[project][table] = {};
                        for(permission in data[table]){
                            if(permission == 'create')
                                $.mm[project][table].create = $.mm.post_based_function(project, table, 'crud_create.php');
                            if(permission == 'read')
                                $.mm[project][table].read = $.mm.get_based_function(project, table);
                            if(permission == 'update')
                                $.mm[project][table].update = $.mm.post_based_function(project, table, 'crud_update.php');
                            if(permission == 'delete')
                                $.mm[project][table].delete = $.mm.post_based_function(project, table, 'crud_delete.php');
                        }
                    }
                    fullfill();
                })
            });
        }
    };
    $.mm.post_based_function = function(project, table, php_file){
        return function(formData){
            return new Promise(function(fullfill, reject){
                $.ajax({
                    type: "POST",
                    url: '/src/'+php_file+'?project='+project+'&table='+table,
                    data: formData,
                    success: function(data) {
                        fullfill(data);
                    },
                    enctype: "multipart/form-data",
                    contentType: false,
                    processData: false
                });
            })
        }
    };
    $.mm.get_based_function = function(project, table) {
        return function(queryString){
            return new Promise(function(fullfill, reject){
                $.get('/src/crud_read.php?project='+project+'&table='+table+'&'+queryString, function(data){
                    fullfill(data);
                })
            });
        }
    };
})(jQuery, window);