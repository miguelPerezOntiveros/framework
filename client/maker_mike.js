(function ($, window, undefined) {
    $.mm = {};
    $.mm.init = function (project) {
        if(project){
            $.mm[project] = {};
            return new Promise(function(fullfill, reject){
                $.get('/src/crud_read.php?project='+$.mm_config['project']+'&permissions=1', function(data){
                    data = JSON.parse(data);
                    for(table in data){
                        $.mm[project][table] = [];
                        for(permission in table){
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
    $.mm.miguelp.awards.read().then(function(response){
        console.log(response);
    })
})

/*
    - client demonstration tool
    - some kind of discovery? maybe fill in a 'tables' array with objects that have appropriate functions
        - I would need a service that returns all tables and the crud actions I can take on them
        - $.mm_get({'table':'portlet'}) vs $.mm.miguelp.portlet.get()
    - constructor (set base endpoint and query parameters in common (table and project))
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