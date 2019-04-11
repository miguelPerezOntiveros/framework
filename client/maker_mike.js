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
                                $.mm[project][table].create = $.mm.create(project, table);
                            if(permission == 'read')
                                $.mm[project][table].read = $.mm.read(project, table);
                            if(permission == 'update')
                                $.mm[project][table].update = $.mm.update(project, table);
                            if(permission == 'delete')
                                $.mm[project][table].delete = $.mm.delete(project, table);
                        }
                    }
                    fullfill();
                })
            });
        }
    };
    $.mm.create = function(project, table) {}
    $.mm.read = function(project, table) {
        return function(queryString){
            return new Promise(function(fullfill, reject){
                $.get('/src/crud_read.php?project='+project+'&table='+table+'&'+queryString, function(data){
                    fullfill(data);
                })
            });
        }
    };
    $.mm.update = function(project, table) {}
    $.mm.delete = function(project, table) {}
})(jQuery, window);


// Usage example
$.mm.init('miguelp').then(function(){
    $.mm.miguelp.award.read().then(function(response){
        console.log(response);
    })
})

/*
    - client demonstration tool
    - endpoints     
        - Authentication
            - get
            - set
        - Create (post)
            - body parameters
                -  id
                - columns[]
        - Read (get)
            - query parameters
                - show
                - only
                - id
                - where
                - equals
                - columns
        - Update (post)
            - body parameters
                -  id
                - columns[]
        - Delete (post)
            - body parameters
                -  id
*/