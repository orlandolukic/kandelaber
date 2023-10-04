

jQuery(document).on('ready', function () {

    let states = [];
    let currentState = {};

    let dataCache = {};
    let pageState = {
        page: "products-page",
        beforeRouteChangeListeners: [], // Array of functions which returns Promises
        afterRouteChangeListeners: [],  // Promises
        props: null,
        isDataSet: false,
        rootContainer: null
    };

    let changeHistory = function() {

    }

    window.addEventListener('popstate', function (e) {

    });

    // Export Route Manager
    window.RouteManager = (function () {
        return {

            init: function() {},

            openRoute: function (route) {},

            routes: {
                openCategory: function () {},
                openSubcategory: function() {},
                openProduct: function() {},
            },

            operations: {
                clearBeforeRouteChangeListener: function() {},
                clearAfterRouteChangeListener: function() {},
                registerBeforeRouteChangeListener: function(id, route, listener) {},
                registerAfterRouteChangeListener: function(id, route, listener) {},

                setProps: function (id, props) {},
                noDataToFetch: function(id) {}
            },

            routes: {
                create: function (id, route) {}
            }


        };
    })();

});
