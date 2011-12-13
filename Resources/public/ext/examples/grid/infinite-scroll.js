Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('Ext.ux', '../ux/');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.grid.PagingScroller'
]);

Ext.onReady(function(){
    Ext.define('ForumThread', {
        extend: 'Ext.data.Model',
        fields: [
            'title', 'forumtitle', 'forumid', 'author',
            {name: 'replycount', type: 'int'},
            {name: 'lastpost', mapping: 'lastpost', type: 'date', dateFormat: 'timestamp'},
            'lastposter', 'excerpt', 'threadid'
        ],
        idProperty: 'threadid'
    });

    // create the Data Store
    var store = Ext.create('Ext.data.Store', {
        id: 'store',
        pageSize: 400,
        model: 'ForumThread',
        remoteSort: true,
        // allow the grid to interact with the paging scroller by buffering
        buffered: true,
        proxy: {
            // load using script tags for cross domain, if the data in on the same domain as
            // this page, an HttpProxy would be better
            type: 'jsonp',
            url: 'http://www.sencha.com/forum/remote_topics/index.php',
            reader: {
                root: 'topics',
                totalProperty: 'totalCount'
            },
            // sends single sort as multi parameter
            simpleSortMode: true
        },
        sorters: [{
            property: 'lastpost',
            direction: 'DESC'
        }]
    });

    function renderTopic(value, p, record) {
        return Ext.String.format(
            '<a href="http://sencha.com/forum/showthread.php?t={2}" target="_blank">{0}</a>',
            value,
            record.data.forumtitle,
            record.getId(),
            record.data.forumid
        );
    }

    var grid = Ext.create('Ext.grid.Panel', {
        width: 700,
        height: 500,
        title: 'ExtJS.com - Browse Forums',
        store: store,
        verticalScrollerType: 'paginggridscroller',
        loadMask: true,
        disableSelection: true,
        invalidateScrollerOnRefresh: false,
        viewConfig: {
            trackOver: false
        },
        // grid columns
        columns:[{
            xtype: 'rownumberer',
            width: 50,
            sortable: false
        },{
            // id assigned so we can apply custom css (e.g. .x-grid-cell-topic b { color:#333 })
            // TODO: This poses an issue in subclasses of Grid now because Headers are now Components
            // therefore the id will be registered in the ComponentManager and conflict. Need a way to
            // add additional CSS classes to the rendered cells.
            id: 'topic',
            text: "Topic",
            dataIndex: 'title',
            flex: 1,
            renderer: renderTopic,
            sortable: false
        },{
            text: "Author",
            dataIndex: 'author',
            width: 100,
            hidden: true,
            sortable: true
        },{
            text: "Replies",
            dataIndex: 'replycount',
            align: 'center',
            width: 70,
            sortable: false
        },{
            id: 'last',
            text: "Last Post",
            dataIndex: 'lastpost',
            width: 130,
            renderer: Ext.util.Format.dateRenderer('n/j/Y g:i A'),
            sortable: true
        }],
        renderTo: Ext.getBody()
    });

    /* Debug code to instrument the scroll gestures on the buffered grid.
    grid.view.on({
        scroll: updateGridData,
        element: 'el'
    })
    store.on({
        load: updateGridData,
        datachanged: updateGridData
    })
    var pg = Ext.create('Ext.grid.property.Grid', {
        style: {
            position: 'absolute'
        },
        x: 735,
        y: 188,
        width: 500,
        width: 300,
        height: 400,
        source: getProperties(),
        renderTo: document.body
    });
    function updateGridData() {
        pg.setSource(getProperties());
    }
    function getProperties() {
        var t = grid.view.el.child('table', true);
        return {
            "Store Total count": store.getTotalCount(),
            "Store count": store.getCount(),
            "Table size": grid.view.el.query('tr').length,
            "Guaranteed start": store.guaranteedStart,
            "Guaranteed end": store.guaranteedEnd,
            "Prefetch start": store.prefetchData.items.length ? store.prefetchData.items[0].index: 'none',
            "Prefetch end": store.prefetchData.items.length ? store.prefetchData.items[store.prefetchData.items.length - 1].index : 'none',
            "View scrollTop": grid.view.el.dom.scrollTop,
            "Table top position": t ? t.style.top : ''
        };
    }
    */

    // trigger the data store load
    store.guaranteeRange(0, 199);
});


